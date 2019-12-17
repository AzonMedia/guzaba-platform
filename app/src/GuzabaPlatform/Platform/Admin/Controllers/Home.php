<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Admin\Controllers;

use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;

class Home extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin'                   => [
                Method::HTTP_GET_HEAD_OPT               => [self::class, 'main']
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() : ResponseInterface
    {

    }
}