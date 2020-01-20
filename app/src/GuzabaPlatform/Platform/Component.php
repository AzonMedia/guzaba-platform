<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform;

use Guzaba2\Base\Base;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInitializationInterface;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInterface;
use GuzabaPlatform\Components\Base\Traits\ComponentTrait;

class Component extends Base implements ComponentInterface, ComponentInitializationInterface
{
    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'FrontendRouter',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    use ComponentTrait;

    protected const COMPONENT_NAME = "CRUD";
    //https://components.platform.guzaba.org/component/{vendor}/{component}
    protected const COMPONENT_URL = 'https://components.platform.guzaba.org/component/guzaba-platform/guzaba-platform';
    //protected const DEV_COMPONENT_URL//this should come from composer.json
    protected const COMPONENT_NAMESPACE = 'GuzabaPlatform\\Platform';
    protected const COMPONENT_VERSION = '0.0.1';//TODO update this to come from the Composer.json file of the component
    protected const VENDOR_NAME = 'Azonmedia';
    protected const VENDOR_URL = 'https://azonmedia.com';
    protected const ERROR_REFERENCE_URL = 'https://error-reference.guzaba.org/error/';

    /**
     * @return array
     */
    public static function run_all_initializations() : array
    {
        self::register_routes();
        return ['register_routes'];
    }

    public static function register_routes() : void
    {
        //register few default routes
        //there are also some hardcoded routes at router.js
        $FrontendRouter = self::get_service('FrontendRouter');
        $FrontendRouter->add_route('/', '@GuzabaPlatform.Platform/views/Home.vue', 'Home');
        $FrontendRouter->add_route('/admin', '@GuzabaPlatform.Platform/views/Admin/Home.vue', 'Admin Home');
        $meta = [
            'in_navigation' => TRUE, //to be shown in the admin navigation
            'additional_template' => '@GuzabaPlatform.Platform/views/Admin/Components/NavigationHook.vue',//here the list of components will be expanded
        ];
        $FrontendRouter->add_route('/admin/components', '@GuzabaPlatform.Platform/views/Admin/Components/Components.vue' ,'Components', $meta);
    }
}