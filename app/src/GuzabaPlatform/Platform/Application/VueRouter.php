<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;


use Azonmedia\Utilities\AlphaNumUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Swoole\Server;
use GuzabaPlatform\Platform\Application\Interfaces\FrontendRouterInterface;
use Guzaba2\Translator\Translator as t;

/**
 * Class VueRouter
 * @package GuzabaPlatform\Platform\Application
 * Used as a service by the components that need to export routes.
 */
class VueRouter extends Base implements FrontendRouterInterface
{

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'Events',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    private array $routes = [];

    private string $router_config_file;

    private bool $routes_dumped_flag = FALSE;

    /**
     * VueRouter constructor.
     * @param string $router_config_file
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     */
    public function __construct(string $router_config_file)
    {
        $this->router_config_file = $router_config_file;
        self::get_service('Events')->add_class_callback(Server::class, '_before_start', [$this, 'dump_routes']);
    }

    public function are_routes_dumped() : bool
    {
        return $this->routes_dumped_flag;
    }

    /**
     * @param string $path URL path like "/something"
     * @param string $component Path to the vue component (may use aliases like '@GuzabaPlatform.Platform/some/template.vue). These will be converted to import()s on dump
     * @param string $name Optional name for the route
     * @throws InvalidArgumentException
     */
    public function add_route(string $path, string $component, string $name='', array $meta = [], array $props = [], int $priority=-1) : void
    {
        if ($this->are_routes_dumped()) {
            throw new RunTimeException(sprintf(t::_('The routes are already dumped to %s. Adding routes at this stage will have no effect.'), $this->get_router_config_file() ));
        }
        if (!empty($meta['in_navigation']) && !$name) {
            throw new InvalidArgumentException(sprintf(t::_('A route that is to be shown in the navigation requires the name argument to be provided.')));
        }

        $matches = 0;
        if ($path === '/') {
            $route_arr = ['/'];
        } elseif ($path[0] === '/') {
            //if the route starts with / remove it to avoid having an empty start element
            //and then prepend it to the first element (as the top levle routes start with /)
            $route = substr($path, 1);
            $route_arr = explode('/', $route);
            $route_arr[0] = '/'.$route_arr[0];
        } else {
            $route_arr = explode('/', $path);
        }

        $pointer =& $this->routes;
        foreach ($route_arr as $route) {
            if (isset($pointer[$route])) {
                $matches++;
                if ($matches === count($route_arr)) {
                    throw new InvalidArgumentException(sprintf(t::_('There is a route with path %s already defined. The component for this path is %s.'), $path, $pointer['component']));
                } else {
                    if (!array_key_exists('children',$pointer[$route])) {
                        $pointer[$route]['children'] = [];
                    }
                    $pointer =& $pointer[$route]['children'];
                }
            } else {
                if ($matches < count($route_arr) - 1) {
                    throw new InvalidArgumentException(sprintf(t::_('It is not allowed to define deeper route %s without first defining all parent segments.'), $path));
                }
                $pointer[$path] = ['path' => $route, 'component' => $component, 'name' => $name];
                if ($meta) {
                    if (!isset($pointer[$path]['meta'])) {
                        $pointer[$path]['meta'] = [];
                    }
                    foreach ($meta as $meta_key => $meta_value) {
                        $pointer[$path]['meta'][$meta_key] = $meta_value;
                    }
                }
                if ($props) {
                    if (!isset($pointer[$path]['props'])) {
                        $pointer[$path]['props'] = [];
                    }
                    foreach ($props as $prop_key => $prop_value) {
                        $pointer[$path]['props'][$prop_key] = $prop_value;
                    }
                }
            }
        }
    }

    /**
     * Returns all routes
     * @return array
     */
    public function get_routes() : array
    {
        return $this->routes;
    }

    /**
     * Returns a single route. Supports child routes if the $route contains /.
     * Returns an empty array if the route is not found.
     * @param string $route
     * @return array
     */
    public function get_route(string $route) : array
    {
        $ret = [];
        if ($route === '/') {
            $route_arr = ['/'];
        } elseif ($route[0] === '/') {
            //if the route starts with / remove it to avoid having an empty start element
            //and then prepend it to the first element (as the top levle routes start with /)
            $route = substr($route, 1);
            $route_arr = explode('/', $route);
            $route_arr[0] = '/'.$route_arr[0];
        } else {
            $route_arr = explode('/', $route);
        }

        $matches = 0;
        $pointer =& $this->routes;
        foreach ($route_arr as $route) {
            if (isset($pointer[$route])) {
                $matches++;
                if ($matches === count($route_arr)) {
                    $pointer =& $pointer[$route];
                    $ret = $pointer;
                    break;
                } else {
                    $pointer =& $pointer[$route]['children'];
                }
            }
        }
        return $ret;
    }

    /**
     * Returns the file to which the configuration is to be dumped
     * @return string
     */
    public function get_router_config_file() : string
    {
        return $this->router_config_file;
    }

    /**
     * Dumps the accumulated routes so far in the $router_config_file file.
     * This method is provided as a callback on the server::_before_start event
     */
    public function dump_routes() : void
    {
        $this->routes_dumped_flag = TRUE;
        $routes_str = 'export default ['.PHP_EOL;
        foreach ($this->routes as $route) {
            $routes_str .= PHP_EOL.$this->dump_route($route);
        }
        $routes_str .= PHP_EOL.'];';
        Kernel::file_put_contents($this->router_config_file, $routes_str);//replace the old file
    }

    private function dump_route(array $route, int $nesting = 0) : string
    {
        if (!empty($route['children'])) {
            $children_str = PHP_EOL.AlphaNumUtil::indent('children: [', $nesting + 1);
            foreach ($route['children'] as $child_route) {
                $children_str .= PHP_EOL.$this->dump_route($child_route, $nesting + 1);
            }
            $children_str .= PHP_EOL.AlphaNumUtil::indent('],', $nesting + 1);
        } else {
            $children_str = '';
        }

        if (!empty($route['meta'])) {
            $meta_str = PHP_EOL.AlphaNumUtil::indent('meta: {'.PHP_EOL, $nesting);
            foreach ($route['meta'] as $meta_name => $meta_value) {
                $meta_str .= AlphaNumUtil::indent("{$meta_name} : '{$meta_value}',".PHP_EOL, $nesting);
            }
            $meta_str .= '},';
        } else {
            $meta_str = '';
        }

        if (!empty($route['props'])) {
            $props_str = PHP_EOL.AlphaNumUtil::indent('props: {'.PHP_EOL, $nesting);
            foreach ($route['props'] as $prop_name => $prop_value) {
                $props_str .= AlphaNumUtil::indent("{$prop_name} : '{$prop_value}',".PHP_EOL, $nesting);
            }
            $props_str .= '},';
        } else {
            $props_str = '';
        }

        $route_str = <<<ROUTE
{
    path: '{$route['path']}',
    name: '{$route['name']}',
    component: () => import('{$route['component']}'),{$meta_str}{$props_str}{$children_str}
},
ROUTE;
        return AlphaNumUtil::indent($route_str, $nesting + 1);
    }
}