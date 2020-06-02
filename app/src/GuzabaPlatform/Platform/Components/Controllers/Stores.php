<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Controllers;


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
                Method::HTTP_GET                            => [self::class, 'main']
            ],
            '/admin/component-stores'            => [
                Method::HTTP_POST                        => [self::class, 'add']
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
            $struct['stores'][] = $Store->get_data();
        }
        return self::get_structured_ok_response($struct);
    }

    public function add(string $store_url): ResponseInterface
    {
        $Store = \GuzabaPlatform\Platform\Components\Models\Store::get_instance_from_url($store_url);
        $Manifest = Manifest::get_default_instance();
        $Manifest->add_store($Store);
        $struct = [
            'message'   => sprintf(t::_('The Store %s with URL %s was added successfully.'), $Store->store_name, $Store->store_url),
        ];
        return self::get_structured_ok_response($struct);
    }
}