<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Http\Method;
use Guzaba2\Routing\ControllerDefaultRoutingMap;
use GuzabaPlatform\Platform\Authentication\Controllers\Auth;
use GuzabaPlatform\Platform\Authentication\Controllers\Login;
use GuzabaPlatform\Platform\Authentication\Controllers\ManageProfile;
use GuzabaPlatform\Platform\Authentication\Controllers\PasswordReset;
use GuzabaPlatform\Platform\Home\Controllers\Home;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Crud\Controllers\Crud;
use GuzabaPlatform\Platform\Crud\Controllers\Permissions;

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
            Method::HTTP_ALL                       => [Home::class, 'main'],
        ],
    ];
}