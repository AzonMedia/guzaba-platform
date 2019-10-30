<?php


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Http\Method;
use Guzaba2\Routing\ControllerDefaultRoutingMap;
use GuzabaPlatform\Platform\Authentication\Controllers\Auth;
use GuzabaPlatform\Platform\Authentication\Controllers\Login;
use GuzabaPlatform\Platform\Authentication\Controllers\ManageProfile;
use GuzabaPlatform\Platform\Authentication\Controllers\PasswordReset;
use GuzabaPlatform\Platform\Home\Controllers\Home;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;

/**
 * Class RoutingMap
 * NO LONGER USED - @see ControllerDefaultRoutingMap
 * A data class containing the routing map for the API.
 * The routes here describe both endpoints for Vue templates and and points that are not used by the front end.
 * @package GuzabaPlatform\Platform\Application
 */
class RoutingMap
{
    public const ROUTING_MAP = [
        '/'                                     => [ //this route serves the front end (index.html)
            Method::HTTP_GET_HEAD_OPT                       => [Home::class, 'main'],
        ],
        GP::API_ROUTE_PREFIX.'/login'                                => [
            Method::HTTP_GET_HEAD_OPT                       => [Login::class, 'main'],
            Method::HTTP_POST                               => [Login::class, 'login'],
        ],
        GP::API_ROUTE_PREFIX.'/manage-profile'                       => [
            Method::HTTP_GET_HEAD_OPT                       => [ManageProfile::class, 'main'],
            Method::HTTP_POST                               => [ManageProfile::class, 'save'],
        ],
        GP::API_ROUTE_PREFIX.'/password-reset'                       => [
            Method::HTTP_GET_HEAD_OPT                       => [PasswordReset::class, 'main'],
            Method::HTTP_POST                               => [PasswordReset::class, 'save'],
        ],
        GP::API_ROUTE_PREFIX.'/user-login'                           => [
            Method::HTTP_GET                                => [Auth::class, 'main'],
            Method::HTTP_POST                               => [Auth::class, 'login'],
        ],
        GP::API_ROUTE_PREFIX.'/user-register'                        => [
            Method::HTTP_POST                               => [Auth::class, 'register'],
        ],
    ];
}