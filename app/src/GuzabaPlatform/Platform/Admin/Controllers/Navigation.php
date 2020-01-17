<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Admin\Controllers;

use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

class Navigation extends BaseController
{

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin-navigation'                   => [
                Method::HTTP_GET_HEAD_OPT               => [self::class, 'main']
            ],
        ],
        'services'      => [
            'FrontendRouter'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() : ResponseInterface
    {

        $links = [];
//        $links[] = [
//            'name'  => t::_('Components'),
//            //'route' => GuzabaPlatform::API_ROUTE_PREFIX.'/components',
//            'route' => '/admin/components',//no api prefix as this is a front end route
//        ];

        $FrontendRouter = self::get_service('FrontendRouter');


        $routes = $FrontendRouter->get_routes();

        if (isset($routes['/admin']['children'])) {
            $routes = $routes['/admin']['children'];
            foreach ($routes as $route) {
                if (!empty($route['meta']['in_navigation'])) {
                    $links[] = ['name' => $route['name'], 'route' => '/admin/'.$route['path']];
                }
            }
        }

        $struct = ['links' => $links];
        $Response = self::get_structured_ok_response($struct);
        return $Response;
    }
}