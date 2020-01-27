<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Controllers;


use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;

class Components extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/components'                   => [
                Method::HTTP_GET_HEAD_OPT               => [self::class, 'main']
            ],
//            '/component'                    => [
//                Method::HTTP_POST                       => [self::class, 'install'],
//            ],
//            '/component/{uuid}'             => [
//                Method::HTTP_PUT | Method::HTTP_PATCH   => [self::class, 'update'],
//            ],
//            '/components'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() : ResponseInterface
    {

    }

//these will be handled by the default controller
//    public function install() : ResponseInterface
//    {
//
//    }
//
//    public function uninstall() : ResponseInterface
//    {
//
//    }
//
//    public function update() : ResponseInterface
//    {
//
//    }

}