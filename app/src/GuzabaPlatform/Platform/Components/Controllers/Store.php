<?php


namespace GuzabaPlatform\Platform\Components\Controllers;


use Guzaba2\Authorization\CurrentUser;
use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Components\Models\Manifest;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

class Store extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin/component-store/'            => [
                Method::HTTP_POST                               => [self::class, 'add'],
            ],
            '/admin/component-store/{store_url}'            => [
                Method::HTTP_GET                                => [self::class, 'main'],
                Method::HTTP_DELETE                             => [self::class, 'remove'],
            ],
        ],
        'services'  => [
            'CurrentUser'
        ]
    ];

    protected const CONFIG_RUNTIME = [];

    public function main(string $store_url): ResponseInterface
    {
        $Store = \GuzabaPlatform\Platform\Components\Models\Store::get_instance_from_manifest($store_url);
        $struct = [];
        //$struct
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

    public function remove(string $store_url): ResponseInterface
    {
        $store_url = base64_decode($store_url);
        $Store = \GuzabaPlatform\Platform\Components\Models\Store::get_instance_from_manifest($store_url);
        $Manifest = Manifest::get_default_instance();
        $Manifest->remove_store($Store);
        return self::get_structured_ok_response( ['message' => sprintf(t::_('The store %s was removed.'), $Store->store_name) ] );
    }

    /**
     * A helper method for morming store data into response.
     * @param Store $Store
     * @return array
     */
    public static function form_store_data(\GuzabaPlatform\Platform\Components\Models\Store $Store): array
    {
        /** @var CurrentUser $CurrentUser */
        $CurrentUser = self::get_service('CurrentUser');
        $ret = [
            'name'                  => $Store->store_name,
            'url'                   => $Store->store_url,
            'added_time'            => $Store->store_added_time,
            'added_time_formatted'  => $Store->store_added_time ? date($CurrentUser->get()->get_date_time_format(), $Store->store_added_time) : '',
            'components_url'        => $Store->store_url.$Store::COMPONENTS_FILE,
            'logo_url'              => $Store->store_url.$Store::LOGO_FILE,
            'icon_url'              => $Store->store_url.$Store::ICON_FILE,
        ];
        return $ret;
    }
}