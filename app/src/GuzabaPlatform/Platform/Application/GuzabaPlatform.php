<?php

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Routing\ControllerDefaultRoutingMap;
use Guzaba2\Routing\ActiveRecordDefaultRoutingMap;
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
use Guzaba2\Http\CorsMiddleware;
use GuzabaPlatform\Platform\Application\AuthCheckMiddleware;
use Azonmedia\Routing\Router;

/**
 * Class Azonmedia
 * @package Azonmedia\Azonmedia\Application
 */
class GuzabaPlatform extends Application
{
    protected const CONFIG_DEFAULTS = [
        'swoole'        => [ //this array will be passed to $SwooleHttpServer->set()
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
                // 'ssl_cert_file'             => NULL,
                // 'ssl_key_file'              => NULL,
            ],

        ],
        'version'       => 'dev',

        'cors_origin'   => 'http://localhost:8081',
        'enable_http2'  => FALSE,//if enabled enable_static_handler/document_root doesnt work
        'enable_ssl'    => FALSE,

        'override_html_content_type' => 'json',//to facilitate debugging when opening the XHR in browser

    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * @var string
     */
    protected string $app_directory = '';

    protected string $generated_files_dir = '';

    public const API_ROUTE_PREFIX = '/api';

    public function __construct($app_directory)
    {
        parent::__construct();

        $this->app_directory = $app_directory;
        $this->generated_files_dir = $this->app_directory.'/startup_generated';

        Kernel::run($this);
    }

    public function __invoke() : int
    {
        return $this->execute();
    }

    public function execute() : int
    {

        $DependencyContainer = new Container();
        Kernel::set_di_container($DependencyContainer);

        $middlewares = [];

        $cli_options_mapping = Kernel::get_runtime_configuration(self::class);
        $runtime_options = array_replace_recursive(self::CONFIG_RUNTIME, $cli_options_mapping);
        $server_options = $runtime_options['swoole']['server_options'];

        if (!empty($server_options['enable_static_handler']) && empty($server_options['document_root'])) {
            $server_options['document_root'] = $this->app_directory.'public/';
        }

        //if (!empty($server_options['open_http2_protocol'])) {
        if ($runtime_options['enable_ssl']) {
            $server_options['ssl_cert_file'] = $this->app_directory.'certificates/localhost.crt';
            $server_options['ssl_key_file'] = $this->app_directory.'certificates/localhost.key';
        }

        if ($runtime_options['enable_http2']) {
            $server_options['open_http2_protocol'] = TRUE;
        }

        //doesnt seem to work properly
        //$swoole_config['static_handler_locations'] = [$this->app_directory.'public/img/'];

        $HttpServer = new \Guzaba2\Swoole\Server($runtime_options['swoole']['host'], $runtime_options['swoole']['port'], $server_options);

        // disable coroutine for debugging
        // $HttpServer->set(['enable_coroutine' => false,]);

        //$ApplicationMiddleware = new ApplicationMiddleware();//blocks static content
        //$RestMiddleware = new RestMiddleware();

        //$Rewriter = new Rewriter(new RewritingRulesArray([]));
        $Rewriter = new UrlRewritingRules('/api/');
        $RewritingMiddleware = new RewritingMiddleware($HttpServer, $Rewriter);

        //$routing_table = RoutingMap::ROUTING_MAP;

        //$Router = new Router(new RoutingMapArray($routing_table));
        $ControllersDefaultRoutingMap = new ControllerDefaultRoutingMap(array_keys(Kernel::get_registered_autoloader_paths()));
        $ModelsDefaultRoutingMap = new ActiveRecordDefaultRoutingMap(array_keys(Kernel::get_registered_autoloader_paths()), self::API_ROUTE_PREFIX );
        $controllers_routing_map = $ControllersDefaultRoutingMap->get_routing_map();
        $controllers_routing_meta_data = $ControllersDefaultRoutingMap->get_all_meta_data();
        $models_routing_map = $ModelsDefaultRoutingMap->get_routing_map();
        $models_routing_meta_data = $ModelsDefaultRoutingMap->get_all_meta_data();

        $routing_map = Router::merge_routes($controllers_routing_map, $models_routing_map);
        $routing_meta_data = array_merge($controllers_routing_meta_data, $models_routing_meta_data);

        //$Router = new Router(new RoutingMapArray($routing_map));
        $Router = new Router(new GeneratedRoutingMap($routing_map, $routing_meta_data, $this->generated_files_dir));
        $RoutingMiddleware = new RoutingMiddleware($HttpServer, $Router);

        $cors_headers = [
            //'Access-Control-Allow-Origin'       => 'http://192.168.0.102:8080',
            'Access-Control-Allow-Origin'       => $runtime_options['cors_origin'],
            'Access-Control-Allow-Credentials'  => 'true',
            'Access-Control-Allow-Methods'      => 'GET,PUT,POST,DELETE,PATCH,OPTIONS',
            'Access-Control-Expose-Headers'     => 'token',
            'Access-Control-Allow-Headers'      => 'token'
        ];

        $CorsMiddleware = new CorsMiddleware($cors_headers);

        //custom middleware for the app
        //$ServingMiddleware = new ServingMiddleware($HttpServer, []);//this serves all requests
        $PlatformMiddleware = new PlatformMiddleware($this, $HttpServer);

        $ExecutorMiddleware = new ExecutorMiddleware($HttpServer, $runtime_options['override_html_content_type']);
        $Authorization = new AuthCheckMiddleware($HttpServer, []);

        //adding middlewares slows down significantly the processing
        //$middlewares[] = $RestMiddleware;
        //$middlewares[] = $ApplicationMiddleware;
        $middlewares[] = $RewritingMiddleware;
        //$middlewares[] = $ServingMiddleware;//this is a custom middleware
        $middlewares[] = $CorsMiddleware;
        $middlewares[] = $Authorization;

        $middlewares[] = $RoutingMiddleware;
        $middlewares[] = $PlatformMiddleware;
        $middlewares[] = $ExecutorMiddleware;



        $DefaultResponseBody = new Stream();
        $DefaultResponseBody->write('Content not found or request not understood (routing not configured).');
        //$DefaultResponseBody = new \Guzaba2\Http\Body\Str();
        //$DefaultResponseBody->write('Content not found or request not understood (routing not configured).');
        $DefaultResponse = new Response(StatusCode::HTTP_NOT_FOUND, [], $DefaultResponseBody);

        $RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $middlewares, $DefaultResponse);

        //$WorkerHandler = new WorkerHandler($HttpServer);
        $WorkerHandler = new WorkerStart($HttpServer);



        $HttpServer->on('WorkerStart', $WorkerHandler);
        $HttpServer->on('Request', $RequestHandler);

        Kernel::printk(PHP_EOL);
        Kernel::printk(sprintf('GuzabaPlatform %s at %s', $runtime_options['version'], $this->app_directory).PHP_EOL);

        $HttpServer->start();

        return Kernel::EXIT_SUCCESS;
    }
}