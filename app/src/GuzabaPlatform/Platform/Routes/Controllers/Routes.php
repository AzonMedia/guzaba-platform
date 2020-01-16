<?php

namespace GuzabaPlatform\Platform\Routes\Controllers;

use Guzaba2\Http\Body\Structured;
use Guzaba2\Http\Method;
use Guzaba2\Mvc\ActiveRecordController;
use Psr\Http\Message\ResponseInterface;

class Routes extends ActiveRecordController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/vue-routes' => [
                Method::HTTP_GET => [self::class, 'main']
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() :ResponseInterface
    {
        $struct = [
            'routes' => [
//the default ones are defined in router.js
//                [
//                    'path'  => '/',
//                    'name'  => 'Home',
//                    'component' => 'Home',//this component is always loaded
//                ],
//                [
//                    'path'  => '/about',
//                    'name'  => 'about',
//                    'component' => '@GuzabaPlatform.Platform/views/About.vue',//will automatically be converted to import
//                ],
            ],
        ];
        //the modules will hook into this one and inject the routes
        $Response = self::get_structured_ok_response($struct);
        return $Response;


//        $routes_str = <<<ROUTES
//routes = [
//    {
//        path: '/',
//        name: 'home',
//        component: Home
//    },
//    {
//        path: '/about',
//        name: 'about',
//        // route level code-splitting
//        // this generates a separate chunk (about.[hash].js) for this route
//        // which is lazy-loaded when the route is visited.
//        component: () =>
//            import(
//                /* webpackChunkName: "about" */
//                //'./GuzabaPlatform/Platform/views/About.vue'
//                '@GuzabaPlatform.Platform/views/About.vue'
//                )
//    },
//    {
//        path: '/admin',
//        name: 'admin',
//        component: () => import('@GuzabaPlatform.Platform/views/Admin/Home.vue')
//    },
//    {
//        path: '/login',
//        name: 'Login',
//        component: Auth
//        // component: () => import('@/components/auth/Login.vue'),
//        // meta: {
//        //     serverPath: 'auth/login'
//        // }
//    },
//    {
//        path: '/logout',
//        name: 'user logout',
//        component: () => store.dispatch('logout')
//    },
//    {
//        path: '/register',
//        name: 'Register',
//        component: Auth
//    },
//    {
//        name: 'not found',
//        path: '*',
//        component: NotFound
//    },
//
//];
//ROUTES;
//
//        $Response = self::get_string_ok_response($routes_str);
//
        //return $Response;
    }

    public static function add_route(string $routes, string $route) : string
    {
        return substr_replace($routes, $route, strrpos( $routes,'];') - 1, 0);
    }
}