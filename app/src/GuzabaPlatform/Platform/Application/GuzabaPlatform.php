<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Azonmedia\Packages\Packages;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Event\Event;
use Guzaba2\Kernel\Interfaces\ClassInitializationInterface;
use Guzaba2\Orm\ClassDeclarationValidation;
use Guzaba2\Routing\ControllerDefaultRoutingMap;
use Guzaba2\Routing\ActiveRecordDefaultRoutingMap;
use Guzaba2\Swoole\Debug\Debugger;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\PlatformMiddleware;
use Guzaba2\Application\Application;
use Guzaba2\Di\Container;
use Guzaba2\Http\Body\Stream;
use Guzaba2\Http\Response;
use Guzaba2\Http\RewritingMiddleware;
use Guzaba2\Http\StatusCode;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Routing\RoutingMiddleware;
use Guzaba2\Swoole\Handlers\WorkerStart;
use Guzaba2\Swoole\Handlers\WorkerConnect;
use Guzaba2\Authorization\Ip\IpFilter;
use Guzaba2\Http\CorsMiddleware;
use GuzabaPlatform\Platform\Application\AuthCheckMiddleware;
use Azonmedia\Routing\Router;
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
        'swoole'                    => [ //this array will be passed to $SwooleHttpServer->set()
            'host'                      => '0.0.0.0',
            'port'                      => 8081,
            'server_options'            => [
                'worker_num'                => NULL,//http workers NULL means Server will set this to swoole_cpu_num()*2
                //Swoole\Coroutine::create(): Unable to use async-io in task processes, please set `task_enable_coroutine` to true.
                //'task_worker_num'   => 8,//tasks workers
                'task_worker_num'           => 0,//tasks workers,
                'document_root'             => NULL,//to be set dynamically
                'enable_static_handler'     => TRUE,
                // 'open_http2_protocol'       => FALSE,//depends on enable-http2 (and enable-ssl)
                'ssl_cert_file'             => NULL,
                'ssl_key_file'              => NULL,

                'http_parse_post'       => FALSE,
                'upload_tmp_dir'        => NULL,//will be set by the application
            ],
            'enable_debug_ports'        => FALSE,
            'base_debug_port'           => Debugger::DEFAULT_BASE_DEBUG_PORT,
        ],
        'version'                   => 'dev',

        'cors_origin'               => 'http://localhost:8080',
        'enable_http2'              => FALSE,//if enabled enable_static_handler/document_root doesnt work
        'enable_ssl'                => FALSE,
        'disable_static_handler'    => FALSE,
        'disable_file_upload'       => FALSE,

        //'upload_max_filesize'       => NULL,//means get from PHP
        //'post_max_size'             => NULL,//means get from PHP

        'log_level'                 => LogLevel::DEBUG,
        'kernel'                    => [
            'disable_all_class_load'            => FALSE,
            'disable_all_class_validation'      => FALSE,
        ],

        'target_language'           => 'en',//this is the default target language
        'skip_translator'           => FALSE,
        //'supported_languages'       => ['en','bg'],
        'supported_languages'       => ['en'],


//        'override_html_content_type' => 'json',//to facilitate debugging when opening the XHR in browser

        'date_time_formats'         => [
            'date_time_format'          => 'Y-m-d H:i:s',
            'time_format'               => 'H:i:s',
            'minutes_format'            => 'H:i',
            'date_format'               => 'Y-m-d',
        ],


        'services'      => [
            'Middlewares',
            'ConnectionFactory',//needed to release all single connections before server start
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    //http://patorjk.com/software/taag/#p=display&f=ANSI%20Shadow&t=guzaba%20platform
    public const PLATFORM_BANNER = <<<BANNER

 ██████╗ ██╗   ██╗███████╗ █████╗ ██████╗  █████╗     ██████╗ ██╗      █████╗ ████████╗███████╗ ██████╗ ██████╗ ███╗   ███╗
██╔════╝ ██║   ██║╚══███╔╝██╔══██╗██╔══██╗██╔══██╗    ██╔══██╗██║     ██╔══██╗╚══██╔══╝██╔════╝██╔═══██╗██╔══██╗████╗ ████║
██║  ███╗██║   ██║  ███╔╝ ███████║██████╔╝███████║    ██████╔╝██║     ███████║   ██║   █████╗  ██║   ██║██████╔╝██╔████╔██║
██║   ██║██║   ██║ ███╔╝  ██╔══██║██╔══██╗██╔══██║    ██╔═══╝ ██║     ██╔══██║   ██║   ██╔══╝  ██║   ██║██╔══██╗██║╚██╔╝██║
╚██████╔╝╚██████╔╝███████╗██║  ██║██████╔╝██║  ██║    ██║     ███████╗██║  ██║   ██║   ██║     ╚██████╔╝██║  ██║██║ ╚═╝ ██║

BANNER;


    /**
     * @var string
     */
    protected string $app_directory = '';

    protected string $generated_files_dir = '';

    public const API_ROUTE_PREFIX = '/api';

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
            die(sprintf(t::_('It appears the Kernel could not find the right path to %s1. This can be a result of the application being moved to another root directory without updating the paths in the ./manifest.json file.'), __FILE__ ));
        }

        Kernel::run($this, self::CONFIG_RUNTIME['kernel']);
    }

    public function __invoke() : int
    {
        return $this->execute();
    }

    /**
     * Returns the path to the ./app directory of the project.
     * @return string
     */
    public function get_app_dir() : string
    {
        return $this->app_directory;
    }

    public function get_date_time_formats() : array
    {
        return self::CONFIG_RUNTIME['date_time_formats'];
    }

    public function execute() : int
    {

        $Watchdog = new \Azonmedia\Watchdog\Watchdog(new \Azonmedia\Watchdog\Backends\SwooleTableBackend());
        Kernel::set_watchdog($Watchdog);

        Kernel::get_di_container()->add('GuzabaPlatform', $this);

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
            $server_options['document_root'] = $this->app_directory.'/public/';
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

        $HttpServer = new \Guzaba2\Swoole\Server(self::CONFIG_RUNTIME['swoole']['host'], self::CONFIG_RUNTIME['swoole']['port'], $server_options);

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
        $RoutingMiddleware = new RoutingMiddleware($HttpServer, $Router);

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
        $PlatformMiddleware = new PlatformMiddleware($this, $HttpServer);

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
        $RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $Middlewares);

        //$ConnectHandler = new WorkerConnect($HttpServer, new IpFilter());
        //$WorkerHandler = new WorkerHandler($HttpServer);
        $WorkerHandler = new WorkerStart($HttpServer, self::CONFIG_RUNTIME['swoole']['enable_debug_ports'], self::CONFIG_RUNTIME['swoole']['base_debug_port']);

        $PipeMessageHandler = new \Guzaba2\Swoole\Handlers\PipeMessage($HttpServer, $Middlewares);

        //$HttpServer->on('Connect', $ConnectHandler);
        $HttpServer->on('WorkerStart', $WorkerHandler);
        $HttpServer->on('Request', $RequestHandler);
        $HttpServer->on('PipeMessage', $PipeMessageHandler);

        Kernel::get_di_container()->add('Server', $HttpServer);

        Kernel::printk(self::PLATFORM_BANNER);
        Kernel::printk(PHP_EOL);
        Kernel::printk(sprintf(t::_('GuzabaPlatform version %s running in %s mode at %s'), self::CONFIG_RUNTIME['version'], static::get_deployment(), $this->app_directory).PHP_EOL);
        Kernel::printk(PHP_EOL);
        if (self::CONFIG_RUNTIME['cors_origin'] !== self::CONFIG_DEFAULTS['cors_origin']) {
            Kernel::printk(sprintf(t::_('CORS origin set to: %1s'), self::CONFIG_RUNTIME['cors_origin']).PHP_EOL);
        } else {
            Kernel::printk(sprintf(t::_('Using the default CORS origin: %1s'), self::CONFIG_RUNTIME['cors_origin']).PHP_EOL);
        }


        //the translator initialization can be moved in start_server.php but then Kernel::printk() cant be used and the start time in Kernel::initialize() will be very wrong
        //Kernel::printk(sprintf('Initializing translations').PHP_EOL);
        //t::initialize(Packages::get_application_composer_file_path(), $packages_filter = ['/azonmedia.*/i', '/guzaba-platform.*/i', '/guzaba.*/i'] );
        //Kernel::printk(sprintf(t::_('Loaded %1s translations from %2s packages.'), t::get_loaded_translations_count(), count(t::get_loaded_packages()) ).PHP_EOL);


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

        //close any single connections that may have been opened during this phase
        self::get_service('ConnectionFactory')->close_all_connections();
        //after the server is started new connections (Pools) will be created for each worker




        $HttpServer->start();

        new Event($this, '_after_execute');

        return Kernel::EXIT_SUCCESS;
    }
}