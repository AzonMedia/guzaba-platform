<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Controllers;


use Guzaba2\Authorization\CurrentUser;
use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Components\Models\Component;
use GuzabaPlatform\Platform\Components\Models\Manifest;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

class Components extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/admin/components'                   => [
                Method::HTTP_GET_HEAD_OPT               => [self::class, 'main']
            ],
        ],
        'services'      => [
            'CurrentUser',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Lists the installed components
     * @return ResponseInterface
     */
    public function main() : ResponseInterface
    {

        $struct = [];
        $struct['components'] = [];
        $Manifest = Manifest::get_default_instance();
        foreach ($Manifest->get_installed_components() as $Component) {
            //$struct['stores'][] = $Store->get_data();
            $struct['components'][] = self::form_component_data($Component);
        }
        return self::get_structured_ok_response($struct);
    }

    public static function form_component_data(Component $Component): array
    {
        /** @var CurrentUser $CurrentUser */
        $CurrentUser = self::get_service('CurrentUser');
        $ret = [
            'package_name'              => $Component->package_name,
            'name'                      => $Component->name,
            'description'               => $Component->description,
            'namespace'                 => $Component->namespace,
            'root_dir'                  => $Component->root_dir,
            'src_dir'                   => $Component->src_dir,
            'public_src_dir'            => $Component->public_src_dir,
            'installed_time'            => $Component->installed_time,
            'installed_time_formatted'  => $Component->installed_time ? date($CurrentUser->get()->get_date_time_format(), $Component->installed_time) : '',
            //TODO -add support for logos and store
//            'components_url'        => $Component->store_url.$Component::COMPONENTS_FILE,
//            'logo_url'              => $Component->store_url.$Component::LOGO_FILE,
//            'icon_url'              => $Component->store_url.$Component::ICON_FILE,
        ];
        return $ret;
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