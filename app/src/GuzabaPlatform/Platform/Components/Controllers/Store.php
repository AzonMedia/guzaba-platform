<?php


namespace GuzabaPlatform\Platform\Components\Controllers;


use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

class Store extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin/component-stores/{store_name}'            => [
                Method::HTTP_GET                            => [self::class, 'main']
            ],
            '/admin/component-stores/{store_name}'            => [
                Method::HTTP_DELETE                         => [self::class, 'remove']
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(): ResponseInterface
    {

    }

    public function remove(): ResponseInterface
    {

    }
}