<?php

declare(strict_types=1);

namespace GuzabaPlatform\Platform\Aliases\Controllers;

use Guzaba2\Http\Method;
use Guzaba2\Orm\ActiveRecord;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;

class Matcher extends BaseController
{

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/get/{alias}'      => [
                Method::HTTP_GET                        => [self::class, 'main'],
            ],
        ],
        'services' => [

        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(string $alias): ResponseInterface
    {
        $Object = ActiveRecord::get_by_alias($alias);
        $struct = $Object->as_array();
        $struct['class'] = get_class($Object);
        return self::get_structured_ok_response($struct);
    }
}