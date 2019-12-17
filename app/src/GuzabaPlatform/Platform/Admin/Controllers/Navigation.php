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
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() : ResponseInterface
    {
        //TODO - print the following links:
        //- Components management
        $links = [];
        $links[] = [
            'name'  => t::_('Components'),
            'route' => GuzabaPlatform::API_ROUTE_PREFIX.'/components',
        ];

        $struct = ['links' => $links];
        $Response = self::get_structured_ok_response($struct);
        return $Response;
    }
}