<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;


use Azonmedia\Utilities\AlphaNumUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
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

    /**
     * @param string $path URL path like "/something"
     * @param string $component Path to the vue component (may use aliases like '@GuzabaPlatform.Platform/some/template.vue). These will be converted to import()s on dump
     * @param string $name Optional name for the route
     * @throws InvalidArgumentException
     */
    public function add_route(string $path, string $component, string $name='') : void
    {
        if (isset($this->routes[$path])) {
            throw new InvalidArgumentException(sprintf(t::_('There is a route with path %s already defined. The component for this path is %s.'), $path, $this->routes[$path]['component']));
        }
        $this->routes[$path] = ['path' => $path, 'component' => $component, 'name' => $name];
    }

    /**
     * @param string $parent_path
     * @param string $path
     * @param string $component
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function add_child_route(string $parent_path, string $path, string $component, string $name = '') : void
    {
        if (!isset($this->routes[$parent_path])) {
            throw new InvalidArgumentException(sprintf(t::_('There is no parent path %s.'), $parent_path));
        }
        if (isset($this->routes[$parent_path]['children'][$path])) {
            throw new InvalidArgumentException(sprintf(t::_('For parent path %s there is a child route with path %s already defined. The component for this child path is %s.'), $parent_path, $path, $this->routes[$parent_path]['children'][$path]['component']));
        }
        $this->routes[$parent_path]['children'][$path] = ['path' => $path, 'component' => $component, 'name' => $name];
    }

    /**
     * Returns all routes
     * @return array
     */
    public function get_all_routes() : array
    {
        return $this->routes;
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
        $routes_str = 'export default generated_routes = ['.PHP_EOL;
        foreach ($this->routes as $route) {
            $routes_str .= $this->dump_route($route);
        }
        $routes_str .= PHP_EOL.'];';
        Kernel::file_put_contents($this->router_config_file, $routes_str);//replace the old file
    }

    private function dump_route(array $route, int $nesting = 0) : string
    {
        if (!empty($route['children'])) {
            $children_str = 'children: ['.PHP_EOL;
            foreach ($route['children'] as $child_route) {
                $children_str .= $this->dump_route($child_route, $nesting + 1);
            }
            $children_str .= '],';
        } else {
            $children_str = '';
        }

        $route_str = <<<ROUTE
{
    path: '{$route['path']}',
    name: '{$route['name']}',
    component: () => import('{$route['component']}'),
    {$children_str}
},

ROUTE;
        return AlphaNumUtil::indent($route_str, $nesting + 1);
    }
}