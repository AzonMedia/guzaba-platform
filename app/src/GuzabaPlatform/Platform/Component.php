<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform;

use Azonmedia\Apm\Profiler;
use Guzaba2\Base\Base;
use Guzaba2\Event\Event;
use Guzaba2\Event\Events;
use Guzaba2\Http\QueueRequestHandler;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Swoole\Handlers\Http\Request;
use GuzabaPlatform\Components\Base\BaseComponent;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInitializationInterface;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInterface;
use GuzabaPlatform\Platform\Application\VueRouter;

class Component extends BaseComponent implements ComponentInterface, ComponentInitializationInterface
{
    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'FrontendRouter',
            'Events',
            'Apm',
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
        self::register_apm_event_hooks();
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

    public static function register_apm_event_hooks(): void
    {
        self::register_request_apm_hooks();
        self::register_middleware_apm_hooks();
        self::register_execute_controller_method_apm_hook();
        self::register_execute_controller_method_check_permissions_apm_hook();
    }

    protected static function register_request_apm_hooks(): void
    {
        /** @var Events $Events */
        $Events = self::get_service('Events');

        $start_time = 0;
        $CallbackStart = static function(Event $Event) use (&$start_time): void
        {
            $start_time = microtime(true);
        };

        $Events->add_class_callback(Request::class, '_before_handle', $CallbackStart);

        $CallbackEnd = static function(Event $Event) use (&$start_time): void
        {
            /** @var Profiler $Apm */
            $Apm = self::get_service('Apm');
            $end_time = microtime(true);
            $Apm->add_key('request_handling_time');
            $Apm->increment_value('request_handling_time', $end_time - $start_time);
        };

        $Events->add_class_callback(Request::class, '_after_handle', $CallbackEnd);
    }

    protected static function register_middleware_apm_hooks(): void
    {
        /** @var Events $Events */
        $Events = self::get_service('Events');

        $start_times = [];
        $CallbackStart = static function(Event $Event) use (&$start_times): void
        {
            $Middleware = $Event->get_arguments()[0];
            $start_times[get_class($Middleware)] = microtime(true);

        };

        $Events->add_class_callback(QueueRequestHandler::class, '_before_middleware_process', $CallbackStart);

        $CallbackEnd = static function(Event $Event) use (&$start_times): void
        {
            /** @var Profiler $Apm */
            $Apm = self::get_service('Apm');
            $end_time = microtime(true);
            $Middleware = $Event->get_arguments()[0];
            $middleware_key = str_replace('\\', '_', get_class($Middleware)).'_handling_time';
            $Apm->add_key($middleware_key);
            $Apm->increment_value($middleware_key, $end_time - $start_times[get_class($Middleware)]);
        };

        $Events->add_class_callback(QueueRequestHandler::class, '_after_middleware_process', $CallbackEnd);
    }

    protected static function register_execute_controller_method_apm_hook(): void
    {
        /** @var Events $Events */
        $Events = self::get_service('Events');

        $start_times = [];
        $CallbackStart = static function(Event $Event) use (&$start_times): void
        {
            list($Controller, $method) = $Event->get_arguments();
            $start_times[get_class($Controller).'::'.$method] = microtime(true);

        };

        $Events->add_class_callback(ExecutorMiddleware::class, '_before_execute_controller_method', $CallbackStart);

        $CallbackEnd = static function(Event $Event) use (&$start_times): void
        {
            /** @var Profiler $Apm */
            $Apm = self::get_service('Apm');
            $end_time = microtime(true);
            list($Controller, $method) = $Event->get_arguments();
            $controller_key = str_replace('\\', '_', get_class($Controller)).'_'.$method.'_execution_time';
            $Apm->add_key($controller_key);
            $Apm->increment_value($controller_key, $end_time - $start_times[get_class($Controller).'::'.$method]);
        };

        $Events->add_class_callback(ExecutorMiddleware::class, '_after_execute_controller_method', $CallbackEnd);
    }

    protected static function register_execute_controller_method_check_permissions_apm_hook(): void
    {
        /** @var Events $Events */
        $Events = self::get_service('Events');

        $start_times = [];
        $CallbackStart = static function(Event $Event) use (&$start_times): void
        {
            list($Controller, $method) = $Event->get_arguments();
            $start_times[get_class($Controller).'::'.$method] = microtime(true);

        };

        $Events->add_class_callback(ExecutorMiddleware::class, '_before_execute_controller_method_check_permissions', $CallbackStart);

        $CallbackEnd = static function(Event $Event) use (&$start_times): void
        {
            /** @var Profiler $Apm */
            $Apm = self::get_service('Apm');
            $end_time = microtime(true);
            list($Controller, $method) = $Event->get_arguments();
            $controller_key = str_replace('\\', '_', get_class($Controller)).'_'.$method.'_check_permissions_time';
            $Apm->add_key($controller_key);
            $Apm->increment_value($controller_key, $end_time - $start_times[get_class($Controller).'::'.$method]);
        };

        $Events->add_class_callback(ExecutorMiddleware::class, '_after_execute_controller_method_check_permissions', $CallbackEnd);
    }
}
