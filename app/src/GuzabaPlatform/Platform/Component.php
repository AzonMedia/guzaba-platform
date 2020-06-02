<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform;

use Guzaba2\Base\Base;
use GuzabaPlatform\Components\Base\BaseComponent;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInitializationInterface;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInterface;
use GuzabaPlatform\Platform\Application\VueRouter;

class Component extends BaseComponent implements ComponentInterface, ComponentInitializationInterface
{
    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'FrontendRouter',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    protected const COMPONENT_NAME = "Guzaba Platform";
    //https://components.platform.guzaba.org/component/{vendor}/{component}
    protected const COMPONENT_URL = 'https://components.platform.guzaba.org/component/guzaba-platform/guzaba-platform';
    //protected const DEV_COMPONENT_URL//this should come from composer.json
    protected const COMPONENT_NAMESPACE = __NAMESPACE__;
    protected const COMPONENT_VERSION = '0.0.1';//TODO update this to come from the Composer.json file of the component
    protected const VENDOR_NAME = 'Azonmedia';
    protected const VENDOR_URL = 'https://azonmedia.com';
    protected const ERROR_REFERENCE_URL = 'https://github.com/AzonMedia/guzaba-platform-docs/tree/master/ErrorReference/';

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
        /** @var VueRouter $FrontendRouter */
        $FrontendRouter = self::get_service('FrontendRouter');
        if (!$FrontendRouter->exists('/')) { //it may already exist defined by the application
            $FrontendRouter->add('/', '@GuzabaPlatform.Platform/views/Home.vue', ['name' => 'Home']);
        }

        $FrontendRouter->add('/admin', '@GuzabaPlatform.Platform/views/Admin/Home.vue', ['name' => 'Admin Home']);
        $additional = [
            'name' => 'Components',
            'meta' => [
                'in_navigation' => TRUE, //to be shown in the admin navigation
                'additional_template' => '@GuzabaPlatform.Platform/views/Admin/Components/ComponentsNavigationHook.vue',//here the list of components will be expanded
            ],
        ];
        $FrontendRouter->{'/admin'}->add('components', '@GuzabaPlatform.Platform/views/Admin/Components/ComponentsAdmin.vue' ,$additional);

        $FrontendRouter->{'/admin'}->add('component-stores', '@GuzabaPlatform.Platform/views/Admin/Components/StoresAdmin.vue' , ['name' => 'Stores'] );
    }
}
