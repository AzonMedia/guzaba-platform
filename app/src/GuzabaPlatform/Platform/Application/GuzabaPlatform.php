<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Azonmedia\Routing\Router;
use Azonmedia\Packages\Packages;
use Azonmedia\Http\Body\Stream;
use Azonmedia\Http\StatusCode;
use Guzaba2\Authorization\Interfaces\AuthorizationProviderInterface;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Event\Event;
use Guzaba2\Kernel\Exceptions\ConfigurationException;
use Guzaba2\Kernel\Interfaces\ClassInitializationInterface;
use Guzaba2\Kernel\Runtime;
use Guzaba2\Orm\ClassDeclarationValidation;
use Guzaba2\Routing\ControllerDefaultRoutingMap;
use Guzaba2\Routing\ActiveRecordDefaultRoutingMap;
use Guzaba2\Swoole\Debug\Debugger;
use Guzaba2\Swoole\Handlers\PipeMessage;
use Guzaba2\Swoole\Handlers\Task;
use Guzaba2\Translator\Translator as t;
use Guzaba2\Application\Application;
use Guzaba2\Di\Container;
use Guzaba2\Http\Response;
use Guzaba2\Http\RewritingMiddleware;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Routing\RoutingMiddleware;
use Guzaba2\Swoole\Handlers\WorkerStart;
use Guzaba2\Swoole\Handlers\WorkerConnect;
use Guzaba2\Authorization\Ip\IpFilter;
use Guzaba2\Http\CorsMiddleware;
use GuzabaPlatform\Platform\Application\AuthCheckMiddleware;
use GuzabaPlatform\Platform\Application\PlatformMiddleware;
use Psr\Log\LogLevel;

/**
 * Class Azonmedia
 * @package Azonmedia\Azonmedia\Application
 *
 * Initializes the application, sets the middlewares and server handlers
 */
class GuzabaPlatform extends Application
{
    protected const CONFIG_DEFAULTS = [
        'swoole'                                    => [ //this array will be passed to $SwooleHttpServer->set()
            'host'                                      => '0.0.0.0',
            'port'                                      => 8081,
            'server_options'                            => [
                'worker_num'                                => NULL,//http workers NULL means Server will set this to swoole_cpu_num()*2
                //Swoole\Coroutine::create(): Unable to use async-io in task processes, please set `task_enable_coroutine` to true.
                //'task_worker_num'   => 8,//tasks workers
                'task_worker_num'                           => 0,//tasks workers,
                'document_root'                             => NULL,//to be set dynamically
                'enable_static_handler'                     => TRUE,
                // 'open_http2_protocol'                    => FALSE,//depends on enable-http2 (and enable-ssl)
                'ssl_cert_file'                             => NULL,
                'ssl_key_file'                              => NULL,

                'http_parse_post'                           => FALSE,
                'upload_tmp_dir'                            => NULL,//will be set by the application
            ],
            'enable_debug_ports'                        => FALSE,
            'base_debug_port'                           => Debugger::DEFAULT_BASE_DEBUG_PORT,
        ],
        'memory_limit'                              => 512,//im MB
        'version'                                   => 'dev',

        'cors_origin'                               => 'http://localhost:8080',
        'enable_http2'                              => FALSE,//if enabled enable_static_handler/document_root doesnt work
        'enable_ssl'                                => FALSE,
        'disable_static_handler'                    => FALSE,
        'disable_file_upload'                       => FALSE,

        //'upload_max_filesize'                     => NULL,//means get from PHP
        //'post_max_size'                           => NULL,//means get from PHP

        'log_level'                                 => LogLevel::DEBUG,
        'kernel'                                    => [
            'disable_all_class_load'                    => FALSE,
            'disable_all_class_validation'              => FALSE,
        ],

        'target_language'                           => 'en',//this is the default target language
        'skip_translator'                           => FALSE,
        //'supported_languages'                     => ['en','bg'],
        'supported_languages'                       => ['en'],

        'allow_no_permission_checks_in_production'  => false,

//        'override_html_content_type' => 'json',//to facilitate debugging when opening the XHR in browser

        'date_time_formats'                         => [
            'date_time_format'                          => 'Y-m-d H:i:s',
            'time_format'                               => 'H:i:s',
            'hrs_mins_format'                           => 'H:i',
            'date_format'                               => 'Y-m-d',
        ],


        'services'                                  => [
            'Middlewares',
            'ConnectionFactory',//needed to release all single connections before server start
            'AuthorizationProvider',//needed to display the startup message which one is used
        ],

        //this is used to set the document_root for Swoole
        'public_dir'                                => '/public',//relative to $this->get_app_dir()
        'data_dir'                                  => '/data',//relative to $this->get_app_dir()
    ];

    protected const CONFIG_RUNTIME = [];

    //http://patorjk.com/software/taag/#p=display&f=ANSI%20Shadow&t=guzaba%20platform
    public const PLATFORM_BANNER = <<<BANNER

 ██████╗ ██╗   ██╗███████╗ █████╗ ██████╗  █████╗     ██████╗ ██╗      █████╗ ████████╗███████╗ ██████╗ ██████╗ ███╗   ███╗
██╔════╝ ██║   ██║╚══███╔╝██╔══██╗██╔══██╗██╔══██╗    ██╔══██╗██║     ██╔══██╗╚══██╔══╝██╔════╝██╔═══██╗██╔══██╗████╗ ████║
██║  ███╗██║   ██║  ███╔╝ ███████║██████╔╝███████║    ██████╔╝██║     ███████║   ██║   █████╗  ██║   ██║██████╔╝██╔████╔██║
██║   ██║██║   ██║ ███╔╝  ██╔══██║██╔══██╗██╔══██║    ██╔═══╝ ██║     ██╔══██║   ██║   ██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║
╚██████╔╝╚██████╔╝███████╗██║  ██║██████╔╝██║  ██║    ██║     ███████╗██║  ██║   ██║   ██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║
 ╚═════╝  ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═════╝ ╚═╝  ╚═╝    ╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝   ╚═╝      ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝
 
BANNER;


    /**
     * @var string
     */
    protected string $app_directory = '';

    protected string $generated_files_dir = '';

    public const API_ROUTE_PREFIX = '/api';

    /**
     * GuzabaPlatform constructor.
     * @param $app_directory
     * @param $generated_files_dir
     * @throws RunTimeException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \Azonmedia\Exceptions\RunTimeException
     * @throws \Guzaba2\Coroutine\Exceptions\ContextDestroyedException
     * @throws \ReflectionException
     */
    public function __construct($app_directory, $generated_files_dir)
    {

        parent::__construct();
        if (!empty(self::CONFIG_RUNTIME['supported_languages'])) {
            t::set_supported_languages(self::CONFIG_RUNTIME['supported_languages']);
        }
        if (!empty(self::CONFIG_RUNTIME['target_language'])) {
            if (in_array(self::CONFIG_RUNTIME['target_language'], self::CONFIG_RUNTIME['supported_languages'], TRUE)) {
                t::set_target_language(self::CONFIG_RUNTIME['target_language']);
            } else {
                $message = sprintf(t::_('The configured target_language "%s" in GuzabaPlatform::CONFIG_RUNTIME[\'target_language\'] is not found in the supported languages "%s" configured in GuzabaPlatform::CONFIG_RUNTIME[\'supported_languages\'].'), self::CONFIG_RUNTIME['target_language'], implode(', ',self::CONFIG_RUNTIME['supported_languages']) );
                throw new RunTimeException($message, 0, NULL, '14e5336f-cf56-41d9-8862-f02789d370f3');
            }
        }

        $this->app_directory = $app_directory;
        $this->generated_files_dir = $generated_files_dir;

        if (!count(self::CONFIG_RUNTIME)) {
            //this would mean that the kernel autoloader did not process the configutation options.
            //This can happen if the kernel has wrong paths and this can happen if the whole project was moved to another root directory but the paths in manifest.json were not updated.
            //throw new RunTimeException(sprintf(t::_()));
            die(sprintf(t::_('It appears the Kernel could not find the right path to %s. This can be a result of the application being moved to another root directory without updating the paths in the ./manifest.json file.'), __FILE__ ));
        }

        Kernel::run($this, self::CONFIG_RUNTIME['kernel']);
    }

    public function __invoke(): int
    {
        return $this->execute();
    }

    /**
     * Returns the path to the ./app directory of the project.
     * @return string
     */
    public function get_app_dir(): string
    {
        return $this->app_directory;
    }

    /**
     * @return string
     */
    public function get_public_dir(): string
    {
        return $this->get_app_dir().self::CONFIG_RUNTIME['public_dir'];
    }

    /**
     * @return string
     */
    public function get_data_dir(): string
    {
        return $this->get_app_dir().self::CONFIG_RUNTIME['public_dir'];
    }

    /**
     * Returns the path to the manifest.json file of the project
     * @return string
     */
    public function get_manifest_file_path(): string
    {
        return realpath($this->app_directory.'/../manifest.json');
    }

    public static function get_date_time_formats(): array
    {
        return self::CONFIG_RUNTIME['date_time_formats'];
    }

    public static function get_date_time_format(): string
    {
        return self::CONFIG_RUNTIME['date_time_formats']['date_time_format'];
    }

    public static function get_time_format(): string
    {
        return self::CONFIG_RUNTIME['date_time_formats']['time_format'];
    }

    public static function get_hrs_mins_format(): string
    {
        return self::CONFIG_RUNTIME['date_time_formats']['hrs_mins_format'];
    }

    public static function get_date_format(): string
    {
        return self::CONFIG_RUNTIME['date_time_formats']['date_format'];
    }

    /**
     * @return int
     * @throws RunTimeException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Coroutine\Exceptions\ContextDestroyedException
     * @throws \ReflectionException
     * @throws \Guzaba2\Kernel\Exceptions\ConfigurationException
     */
    public function execute() : int
    {
        if (in_array( Kernel::get_php_sapi_name(), [ Kernel::SAPI['CLI'], Kernel::SAPI['SWOOLE'] ] ) ) {
            $Watchdog = new \Azonmedia\Watchdog\Watchdog(new \Azonmedia\Watchdog\Backends\SwooleTableBackend());
            Kernel::set_watchdog($Watchdog);
        }


        Kernel::get_di_container()->add('GuzabaPlatform', $this);

        Runtime::raise_memory_limit(self::CONFIG_RUNTIME['memory_limit'] * 1024 * 1024);

        new Event($this, '_before_execute');


        $server_options = self::CONFIG_RUNTIME['swoole']['server_options'];

        if (self::is_production()) {
            $server_options['daemonize'] = TRUE;
            $server_options['log_file'] = $this->app_directory.'/logs/swoole_log.txt';
        }
        if (!empty(self::CONFIG_RUNTIME['disable_static_handler'])) {
            $server_options['enable_static_handler'] = FALSE;
            unset($server_options['document_root']);
        }

        if (!empty($server_options['enable_static_handler']) && empty($server_options['document_root'])) {
            //$server_options['document_root'] = $this->app_directory.'/public/';
            $server_options['document_root'] = $this->get_public_dir();
        }

        if (empty(self::CONFIG_RUNTIME['disable_file_upload'])) {
            $server_options['upload_tmp_dir'] = $this->app_directory.'/uploads_temp_dir';
        }

        //if (!empty($server_options['open_http2_protocol'])) {
        if (self::CONFIG_RUNTIME['enable_ssl']) {
            $server_options['ssl_cert_file'] = $server_options['ssl_cert_file'] ? $server_options['ssl_cert_file'] : $this->app_directory.'/certificates/localhost.crt';
            $server_options['ssl_key_file'] = $server_options['ssl_key_file'] ? $server_options['ssl_key_file'] : $this->app_directory.'/certificates/localhost.key';
        } else {
            unset($server_options['ssl_cert_file']);
            unset($server_options['ssl_key_file']);
        }

        if (self::CONFIG_RUNTIME['enable_http2']) {
            $server_options['open_http2_protocol'] = TRUE;
        }

//        if (in_array( Kernel::get_php_sapi_name(), [ Kernel::SAPI['CLI'], Kernel::SAPI['SWOOLE'] ] ) ) {
//            $HttpServer = new \Guzaba2\Swoole\Server(self::CONFIG_RUNTIME['swoole']['host'], self::CONFIG_RUNTIME['swoole']['port'], $server_options);
//        } else {
//
//        }

        // disable coroutine for debugging
        // $HttpServer->set(['enable_coroutine' => false,]);

        //$ApplicationMiddleware = new ApplicationMiddleware();//blocks static content
        //$RestMiddleware = new RestMiddleware();

        //$Rewriter = new Rewriter(new RewritingRulesArray([]));
        //$Rewriter = new UrlRewritingRules( ['/api/'] );
        $Rewriter = new UrlRewritingRules( [self::API_ROUTE_PREFIX.'/'] );
        //$RewritingMiddleware = new RewritingMiddleware($HttpServer, $Rewriter);
        $RewritingMiddleware = new RewritingMiddleware($Rewriter);

        $static_routing_map = RoutingMap::ROUTING_MAP;

        //$Router = new Router(new RoutingMapArray($routing_table));
        //$ControllersDefaultRoutingMap = new ControllerDefaultRoutingMap(array_keys(Kernel::get_registered_autoloader_paths()));
        //$ModelsDefaultRoutingMap = new ActiveRecordDefaultRoutingMap(array_keys(Kernel::get_registered_autoloader_paths()), self::API_ROUTE_PREFIX );
        $ModelsDefaultRoutingMap = new ActiveRecordDefaultRoutingMap(array_keys(Kernel::get_registered_autoloader_paths()), self::CONFIG_RUNTIME['supported_languages'] ?? []);
        //$controllers_routing_map = $ControllersDefaultRoutingMap->get_routing_map();
        //$controllers_routing_meta_data = $ControllersDefaultRoutingMap->get_all_meta_data();
        $models_routing_map = $ModelsDefaultRoutingMap->get_routing_map();
        $models_routing_map_with_prefix = [];
        foreach ($models_routing_map as $key=>$value) {
            $models_routing_map_with_prefix[self::API_ROUTE_PREFIX.$key] = $value;
        }
        $models_routing_meta_data = $ModelsDefaultRoutingMap->get_all_meta_data();
        foreach ($models_routing_meta_data as $key=>$value) {
            $models_routing_meta_data_with_prefix[self::API_ROUTE_PREFIX.$key] = $value;
        }

        //$routing_map = Router::merge_routes($controllers_routing_map, $models_routing_map);
        //$routing_meta_data = array_merge($controllers_routing_meta_data, $models_routing_meta_data);
        //$routing_map = $models_routing_map;
        //$routing_meta_data = $models_routing_meta_data;
        //$routing_map = Router::merge_routes($static_routing_map, $models_routing_map);
        $routing_map = Router::merge_routes($static_routing_map, $models_routing_map_with_prefix);
        $routing_meta_data = array_merge([], $models_routing_meta_data_with_prefix);

        //$Router = new Router(new RoutingMapArray($routing_map));
        $Router = new Router(new GeneratedRoutingMap($routing_map, $routing_meta_data, $this->generated_files_dir));
        Kernel::get_di_container()->add('Router', $Router);//thew router may be needed outside the middlewares... for verifying routes for example

        //this creates a mapping between controller class:method to route
        //so that in the front end no routes get hardcoded (but instead class:method is used)
        $RoutesMap = new RoutesMap($Router, $this->app_directory.'/public_src/components_config/routes_map.config.js');
        
        //$RoutingMiddleware = new RoutingMiddleware($HttpServer, $Router);
        $RoutingMiddleware = new RoutingMiddleware($Router);

        $cors_headers = [
            //'Access-Control-Allow-Origin'       => 'http://192.168.0.102:8080',
            'Access-Control-Allow-Origin'       => self::CONFIG_RUNTIME['cors_origin'],
            'Access-Control-Allow-Credentials'  => 'true',
            'Access-Control-Allow-Methods'      => 'GET,PUT,POST,DELETE,PATCH,OPTIONS',
            'Access-Control-Expose-Headers'     => 'token',
            'Access-Control-Allow-Headers'      => 'token, content-type',
        ];

        $CorsMiddleware = new CorsMiddleware($cors_headers);

        //custom middleware for the app
        //$ServingMiddleware = new ServingMiddleware($HttpServer, []);//this serves all requests
        //$PlatformMiddleware = new PlatformMiddleware($this, $HttpServer);
        $PlatformMiddleware = new PlatformMiddleware();

        //$ExecutorMiddleware = new ExecutorMiddleware($HttpServer, self::CONFIG_RUNTIME['override_html_content_type']);
        $ExecutorMiddleware = new ExecutorMiddleware();
        //$ExecutorMiddleware = new ExecutorMiddleware($HttpServer);
        //$Authorization = new AuthCheckMiddleware($HttpServer, []);
//      $middlewares = [];
//        //adding middlewares slows down significantly the processing
//        //$middlewares[] = $RestMiddleware;
//        //$middlewares[] = $ApplicationMiddleware;
//        $middlewares[] = $RewritingMiddleware;
//        //$middlewares[] = $ServingMiddleware;//this is a custom middleware
//        $middlewares[] = $CorsMiddleware;
//        $middlewares[] = $Authorization;
//        $middlewares[] = $RoutingMiddleware;
//        $middlewares[] = $PlatformMiddleware;
//        $middlewares[] = $ExecutorMiddleware;

        //here more middlewares can be injected by the components
        //the component can inject middlewares in the manifest.json in its PostInstall hook
        //the component may also inject dependencies in the Di in the registry/dev.php
        //a component can inject middlewares using a class implementing ClassInitializationInterface and getting the service Middlewares and using the add() with BeforeMiddleware argument
        //to in inject a middleware before the Execution


        $Middlewares = self::get_service('Middlewares');
        new Event($Middlewares, '_before_setup');
        $Middlewares->add_multiple($RewritingMiddleware, $CorsMiddleware, $RoutingMiddleware, $PlatformMiddleware, $ExecutorMiddleware);
        //there must be a way to reorder the middlewares and this order to be stored for the next restart
        new Event($Middlewares, '_after_setup');


        

        /*
        $DefaultResponseBody = new Stream();
        $DefaultResponseBody->write('Content not found or request not understood/route not found.');
        //$DefaultResponseBody = new \Guzaba2\Http\Body\Str();
        //$DefaultResponseBody->write('Content not found or request not understood (routing not configured).');
        $DefaultResponse = new Response(StatusCode::HTTP_NOT_FOUND, [], $DefaultResponseBody);
        */


        //$RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $middlewares, $DefaultResponse);
        //$RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $Middlewares->get_middlewares(), $DefaultResponse);
        //$RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $Middlewares, $DefaultResponse, $ServerErrorReponse);
        if (in_array( Kernel::get_php_sapi_name(), [ Kernel::SAPI['CLI'], Kernel::SAPI['SWOOLE'] ] ) ) {

            $HttpServer = new \Guzaba2\Swoole\Server(self::CONFIG_RUNTIME['swoole']['host'], self::CONFIG_RUNTIME['swoole']['port'], $server_options);
            Kernel::get_di_container()->add('Server', $HttpServer);

            $RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $Middlewares);
            //$ConnectHandler = new WorkerConnect($HttpServer, new IpFilter());
            //$WorkerHandler = new WorkerHandler($HttpServer);
            $WorkerHandler = new WorkerStart($HttpServer, self::CONFIG_RUNTIME['swoole']['enable_debug_ports'], self::CONFIG_RUNTIME['swoole']['base_debug_port']);
            $PipeMessageHandler = new PipeMessage($HttpServer, $Middlewares);
            $TaskHandler = new Task($HttpServer, $Middlewares);
            //$HttpServer->on('Connect', $ConnectHandler);
            $HttpServer->on('WorkerStart', $WorkerHandler);
            $HttpServer->on('Request', $RequestHandler);
            $HttpServer->on('PipeMessage', $PipeMessageHandler);
            $HttpServer->on('Task', $TaskHandler);



            Kernel::printk(self::PLATFORM_BANNER);
            Kernel::printk(PHP_EOL);
            Kernel::printk(sprintf(t::_('GuzabaPlatform version %s running in %s mode at %s'), self::CONFIG_RUNTIME['version'], static::get_deployment(), $this->app_directory).PHP_EOL);
            Kernel::printk(PHP_EOL);
            if (self::CONFIG_RUNTIME['cors_origin'] !== self::CONFIG_DEFAULTS['cors_origin']) {
                Kernel::printk(sprintf(t::_('CORS origin set to: %1$s'), self::CONFIG_RUNTIME['cors_origin']).PHP_EOL);
            } else {
                Kernel::printk(sprintf(t::_('Using the default CORS origin: %1$s'), self::CONFIG_RUNTIME['cors_origin']).PHP_EOL);
            }

            self::authorization_provider_messages();


            //the translator initialization can be moved in start_server.php but then Kernel::printk() cant be used and the start time in Kernel::initialize() will be very wrong
            //Kernel::printk(sprintf('Initializing translations').PHP_EOL);
            //t::initialize(Packages::get_application_composer_file_path(), $packages_filter = ['/azonmedia.*/i', '/guzaba-platform.*/i', '/guzaba.*/i'] );
            //Kernel::printk(sprintf(t::_('Loaded %1$s translations from %2$s packages.'), t::get_loaded_translations_count(), count(t::get_loaded_packages()) ).PHP_EOL);


            $root_directory = realpath($this->app_directory.'/../');
            $Manifest = json_decode(file_get_contents($root_directory.'/manifest.json'));
            $components_info = t::_('Installed components:').PHP_EOL;
            foreach ($Manifest->components as $Component) {
                $components_info .= str_repeat(' ',4).'- '.$Component->name.' - '.$Component->namespace.' : '.$Component->src_dir.PHP_EOL;
            }
            Kernel::printk($components_info);
            Kernel::printk(PHP_EOL);

            $middlewares_info = t::_('Middlewares:').PHP_EOL;
            //foreach ($middlewares as $Middleware) {
            //foreach ($Middlewares->get_middlewares() as $Middleware) {
            foreach ($Middlewares as $Middleware) {
                $middlewares_info .= str_repeat(' ',4).'- '.get_class($Middleware).' - '.((new \ReflectionClass($Middleware))->getFileName()).PHP_EOL;
            }
            Kernel::printk($middlewares_info);
            Kernel::printk(PHP_EOL);

            //close any single connections that may have been opened during this phase
            self::get_service('ConnectionFactory')->close_all_connections();
            //after the server is started new connections (Pools) will be created for each worker


            $HttpServer->start();

        } else {
            $HttpServer = new \Guzaba2\Httpd\Server($_SERVER['SERVER_NAME'], (int) $_SERVER['SERVER_PORT'], []);
            Kernel::get_di_container()->add('Server', $HttpServer);

            $RequestHandler = new \Guzaba2\Httpd\Handlers\Request($HttpServer, $Middlewares);

            $HttpServer->on('Request', $RequestHandler);
            $HttpServer->handle('Request');

        }

        //new Event($this, '_after_execute');//this is not reached after the server is started

        return Kernel::EXIT_SUCCESS;
    }

    private static function authorization_provider_messages(): void
    {
        /** @var AuthorizationProviderInterface $AuthorizationProvider */
        $AuthorizationProvider = self::get_service('AuthorizationProvider');
        Kernel::printk(sprintf(t::_('Authorization provider: %1$s'), get_class($AuthorizationProvider)));
        Kernel::printk(PHP_EOL);
        if (!$AuthorizationProvider::checks_permissions()) {
            Kernel::printk(sprintf(t::_('The %1$s authorization provider DOES NOT check/enforce permissions!'), get_class($AuthorizationProvider)), LogLevel::WARNING);
            Kernel::printk(PHP_EOL);
            if (self::is_production()) {
                if (!self::CONFIG_RUNTIME['allow_no_permission_checks_in_production']) {
                    $raw_message = <<<'RAW'
                        It is not allowed to run in production mode with authorization provider (AuthorizationProvider service) that does not check/enforce permissions.
                        To bypass this limitation please start the server with the --allow-no-permission-checks-in-production startup option.
                        RAW;
                    $message = sprintf(t::_($raw_message));
                    throw new ConfigurationException($message, 0, null, '3b0d54d3-1662-4e84-9f7a-971a30baef83');
                } else {

                    $raw_message = <<<'RAW'
                        !!!!!!!!!! Running in PRODUCTION with AuthorizationProvider service set to %1$s which is a non-enforcing one !!!!!!!!!! 
                        RAW;
                    $message = sprintf(t::_($raw_message), get_class($AuthorizationProvider));
                    Kernel::printk($message);
                    Kernel::printk(PHP_EOL);

                    $raw_message = <<<'RAW'
                        %1$s::CONFIG_RUNTIME['allow-no-permission-checks-in-production'] = true probably set with --allow-no-permission-checks-in-production from CLI
                        RAW;
                    $message = sprintf(t::_($raw_message),__CLASS__);
                    Kernel::printk($message);
                    Kernel::printk(PHP_EOL);
                }
            }
        }
    }
}