<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Controllers;


use Guzaba2\Authorization\CurrentUser;
use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Components\Models\Manifest;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

class Stores extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin/component-stores'            => [
                Method::HTTP_GET                         => [self::class, 'main']
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(): ResponseInterface
    {
        $struct = [];
        $struct['stores'] = [];
        $Manifest = Manifest::get_default_instance();
        foreach ($Manifest->get_stores() as $Store) {
            //$struct['stores'][] = $Store->get_data();
            $struct['stores'][] = Store::form_store_data($Store);
        }
        return self::get_structured_ok_response($struct);
    }



}