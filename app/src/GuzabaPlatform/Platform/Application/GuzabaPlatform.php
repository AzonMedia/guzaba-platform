<?php

namespace GuzabaPlatform\Platform\Application;

use GuzabaPlatform\Platform\Home\Controllers\Home;
use Azonmedia\Routing\Router;
use Azonmedia\Routing\RoutingMapArray;
use Azonmedia\UrlRewriting\Rewriter;
use Azonmedia\UrlRewriting\RewritingRulesArray;
use Guzaba2\Application\Application;
use Guzaba2\Di\Container;
use Guzaba2\Http\Body\Str;
use Guzaba2\Http\Body\Stream;
use Guzaba2\Http\Method;
use Guzaba2\Http\Response;
use Guzaba2\Http\RewritingMiddleware;
use Guzaba2\Http\StatusCode;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Mvc\RestMiddleware;
use Guzaba2\Mvc\RoutingMiddleware;
use Guzaba2\Swoole\ApplicationMiddleware;
use Guzaba2\Swoole\Handlers\WorkerStart;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Http\CorsMiddleware;


/**
 * Class Azonmedia
 * @package Azonmedia\Azonmedia\Application
 */
class GuzabaPlatform extends Application
{
    protected const CONFIG_DEFAULTS = [
        'swoole' => [ //this array will be passed to $SwooleHttpServer->set()
            'host'                      => '0.0.0.0',
            'port'                      => 8082,
            'server_options'            => [
                'worker_num'                => 24,//http workers
                //Swoole\Coroutine::create(): Unable to use async-io in task processes, please set `task_enable_coroutine` to true.
                //'task_worker_num'   => 8,//tasks workers
                'task_worker_num'           => 0,//tasks workers,
                'document_root'             => NULL,//to be set dynamically
                'enable_static_handler'     => TRUE,
            ],

        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * @var string
     */
    protected $app_directory;

    public function __construct($app_directory)
    {
        parent::__construct();

        $this->app_directory = $app_directory;

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

        $server_options = self::CONFIG_RUNTIME['swoole']['server_options'];
        if (!empty($server_options['enable_static_handler']) && empty($server_options['document_root'])) {
            $server_options['document_root'] = $this->app_directory.'public/';
        }

        //doesnt seem to work properly
        //$swoole_config['static_handler_locations'] = [$this->app_directory.'public/img/'];



        $HttpServer = new \Guzaba2\Swoole\Server(self::CONFIG_RUNTIME['swoole']['host'], self::CONFIG_RUNTIME['swoole']['port'], $server_options);

        // disable coroutine for debugging
        // $HttpServer->set(['enable_coroutine' => false,]);

        $ApplicationMiddleware = new ApplicationMiddleware();//blocks static content
        $RestMiddleware = new RestMiddleware();

        $Rewriter = new Rewriter(new RewritingRulesArray([]));
        $RewritingMiddleware = new RewritingMiddleware($HttpServer, $Rewriter);

        $routing_table = [
            '/'                                     => [
                Method::HTTP_GET                        => [Home::class, 'main'],
            ],
            '/lets-talk'                            => [
                Method::HTTP_GET                        => [Home::class, 'talk'],
            ]
        ];
        $Router = new Router(new RoutingMapArray($routing_table));
        $RoutingMiddleware = new RoutingMiddleware($HttpServer, $Router);

        $CorsMiddleware = new CorsMiddleware();

        //custom middleware for the app
        //$ServingMiddleware = new ServingMiddleware($HttpServer, []);//this serves all requests
        //$GlogMiddleware = new GlogMiddleware($this, $HttpServer);

        $ExecutorMiddleware = new ExecutorMiddleware($HttpServer);

        //adding middlewares slows down significantly the processing
        //$middlewares[] = $RestMiddleware;
        //$middlewares[] = $ApplicationMiddleware;
        //$middlewares[] = $RewritingMiddleware;
        $middlewares[] = $RoutingMiddleware;
        //$middlewares[] = $ServingMiddleware;//this is a custom middleware
        //$middlewares[] = $GlogMiddleware;//custom middleware used by this app - disables locking on ActiveRecord on read (get) requests

        $middlewares[] = $CorsMiddleware;
        $middlewares[] = $ExecutorMiddleware;



        $DefaultResponseBody = new Stream();
        $DefaultResponseBody->write('Content not found or request not understood (routing not configured).');
        //$DefaultResponseBody = new \Guzaba2\Http\Body\Str();
        //$DefaultResponseBody->write('Content not found or request not understood (routing not configured).');
        $DefaultResponse = new \Guzaba2\Http\Response(StatusCode::HTTP_NOT_FOUND, [], $DefaultResponseBody);

        $RequestHandler = new \Guzaba2\Swoole\Handlers\Http\Request($HttpServer, $middlewares, $DefaultResponse);

        //$WorkerHandler = new WorkerHandler($HttpServer);
        $WorkerHandler = new WorkerStart($HttpServer);



        $HttpServer->on('WorkerStart', $WorkerHandler);
        $HttpServer->on('Request', $RequestHandler);


        $HttpServer->start();

        return Kernel::EXIT_SUCCESS;
    }
}