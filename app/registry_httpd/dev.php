<?php

use Azonmedia\Apm\NullBackend;
use Azonmedia\Apm\Profiler;
use Azonmedia\Lock\Backends\SwooleTableBackend;
use Azonmedia\Lock\LockManager;
use Guzaba2\Authorization\Acl\AclAuthorizationProvider;
use Guzaba2\Authorization\Acl\AclCreateAuthorizationProvider;
use Guzaba2\Authorization\CurrentUser;
use Guzaba2\Cache\ContextCache;
use Guzaba2\Cache\MemoryCache;
use Guzaba2\Cache\RedisCache;
use Guzaba2\Cache\SwooleTableCache;
use Guzaba2\Cache\SwooleTableIntCache;
use GuzabaPlatform\Platform\Application\Middlewares;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Guzaba2\Database\ConnectionFactory;
use Guzaba2\Database\ConnectionProviders\Basic;
use Guzaba2\Database\ConnectionProviders\Pool;
use Guzaba2\Database\Sql\QueryCache;
use Guzaba2\Di\Container;
use Guzaba2\Event\Events;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Orm\MetaStore\NullMetaStore;
use Guzaba2\Orm\MetaStore\SwooleTable;
use Guzaba2\Orm\Store\Memory;
use Guzaba2\Orm\Store\Nosql\Redis;
use Guzaba2\Orm\Store\NullStore;
use Guzaba2\Orm\Store\Sql\Mysql;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use GuzabaPlatform\Platform\Application\MysqlConnection;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use GuzabaPlatform\Platform\Application\RedisConnection;
use GuzabaPlatform\Platform\Application\VueRouter;
use Guzaba2\Orm\BlockingStore\Nosql\MongoDB;
use GuzabaPlatform\Platform\Application\MongoDbConnection;
use Guzaba2\Authorization\BypassAuthorizationProvider;
use Guzaba2\Transaction\TransactionManager;
use Psr\Log\LogLevel;

return [
    GuzabaPlatform::class => [
        'swoole'        => [ //this array will be passed to $SwooleHttpServer->set()
            'host'                      => '0.0.0.0',
            'port'                      => 8081,
            'server_options'            => [
                'worker_num'                => 8,//http workers NULL means Server will set this to swoole_cpu_num()*2
                //Swoole\Coroutine::create(): Unable to use async-io in task processes, please set `task_enable_coroutine` to true.
                //'task_worker_num'   => 8,//tasks workers
                'task_worker_num'           => 4,//tasks workers,
                'document_root'             => NULL,//to be set dynamically
                'enable_static_handler'     => TRUE,
                // 'open_http2_protocol'       => FALSE,//depends on enable-http2 (and enable-ssl)
                // 'ssl_cert_file'             => NULL,
                // 'ssl_key_file'              => NULL,
            ],
        ],
        'version'       => 'dev',
        //'cors_origin'   => 'http://localhost:8080',
        'enable_http2'  => FALSE,//if enabled enable_static_handler/document_root doesnt work
        'enable_ssl'    => FALSE,
        'log_level'     => LogLevel::DEBUG,
        'override_html_content_type' => 'json',//to facilitate debugging when opening the XHR in browser
    ],
    Pool::class         => [
        'max_connections'   => 5,
    ],
    /**
     * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     * It is important to note that during Runtime new services can also be defined.
     * @see GuzabaPlatform::execute()
     * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     */
    Container::class    => [
        'dependencies' => [
            'ConnectionFactory'             => [
                'class'                         => ConnectionFactory::class,
                'args'                          => [
                    'ConnectionProvider'            => 'ConnectionProviderBasic',
                    //'ConnectionProvider'            => 'ConnectionProviderBasic',
                ],
            ],
            'ConnectionProviderPool'        => [
                'class'                         => Pool::class,
                'args'                          => [],
            ],
            'ConnectionProviderBasic'       => [
                'class'                         => Basic::class,
                'args'                          => [],
            ],
            // MongoDBStore
            // 'OrmStore'                      => [
            //     'class'                         => Memory::class,//the Memory store is the first to be looked into
            //     'args'                          => [
            //         'FallbackStore'                 => 'MongoDbOrmStore',
            //     ],
            // ],
            // 'MongoDbOrmStore'                 => [
            //     'class'                         => MongoDB::class,
            //     'args'                          => [
            //         'FallbackStore'                 => 'MysqlOrmStore',
            //         'connection_class'              => MongoDbConnection::class,
            //     ],
            // ],

//            'OrmStore'                      => [
//                'class'                         => Memory::class,//the Memory store is the first to be looked into
//                'args'                          => [
//                    //'FallbackStore'                 => 'RedisOrmStore',
//                    'FallbackStore'                 => 'MysqlOrmStore',
//                ],
//                //'type'                          => 'worker',//no need - let it be global - there is a separate event on server start
//            ],
//            'RedisOrmStore'                 => [
//                'class'                         => Redis::class,
//                'args'                          => [
//                    'FallbackStore'                 => 'MysqlOrmStore',
//                    'connection_class'              => RedisConnection::class,
//                ],
//            ],
//            'RedisCo'                       => [
//                'class'                         => RedisConnection::class,
//                'args'                          => [
//                ],
//            ],
            'OrmStore'                 => [
                'class'                         => Mysql::class,
                'args'                          => [
                    'FallbackStore'                 => 'NullOrmStore',
                    'connection_class'              => MysqlConnectionCoroutine::class,
                    'no_coroutine_connection_class' => MysqlConnection::class,
                ]
            ],
            'NullOrmStore'                  => [
                'class'                         => NullStore::class,
                'args'                          => [
                    'FallbackStore'                 => NULL,
                ],
            ],
//            'OrmMetaStore'                  => [
//                'class'                         => SwooleTable::class,
//                'args'                          => [
//                    'FallbackMetaStore'             => 'NullOrmMetaStore',
//                ],
//                'initialize_immediately'        => TRUE,
//                //'type'                          => 'worker',//this is a swoole table - can not be worker as this needs to be global - swoole table must be initialized before workers
//            ],
            //apache & cgi SAPI specific
            'OrmMetaStore'              => [
                'class'                         => NullMetaStore::class,
                'args'                          => [
                    'FallbackStore'                 => NULL,
                ],
            ],
//            'BlankOrmStore'                 => [
//                'class'                         => BlankOrmStore::class,
//                'args'                          => [],
//            ],
            'QueryCache' => [
                'class'                         => QueryCache::class,
                'args'                          => [
                    'TimeCache'                     => 'TimeCache',
                    'Cache'                         => 'WorkerCache',
                ],
            ],
            'LockManager'                   => [
                //'class'                         => CoroutineLockManager::class,
                'class'                         => LockManager::class,
                'args'                          => [
                    'Backend'                       => 'LockManagerBackend',
                    'Logger'                        => [Kernel::class, 'get_logger'],
                ],
                'type'                          => 'coroutine',
            ],
            'LockManagerBackend'            => [
                //'class'                         => SwooleTableBackend::class,
                'class'                         => \Azonmedia\Lock\Backends\NullBackend::class,
                'args'                          => [
                    'Logger'                        => [Kernel::class, 'get_logger'],
                ],
                'initialize_immediately'        => TRUE,
            ],
            'AuthorizationProvider'         => [
                //'class'                         => BypassAuthorizationProvider::class,
                //'class'                         => AclAuthorizationProvider::class,
                'class'                         => AclCreateAuthorizationProvider::class,
                'args'                          => [],
            ],
            'CurrentUser'                   => [
                'class'                         => CurrentUser::class,
                'args'                          => [
                    'User'                          => 'DefaultCurrentUser',
                    //'user_id'                       => 1,
                    //'user_class'                    => User::class,
                ],
                'type'                          => 'coroutine',
            ],
            'DefaultCurrentUser'            => [
                'class'                         => User::class,
                'args'                          => [
                    //'index'                         => 1,
                    'index'                         => '5c40bffb-f972-11e9-8f16-002564a26d87',
                    'read_only'                     => TRUE,
                    'permission_checks_disabled'    => TRUE,
                ],
                'depends_on'                    => [
                    'LockManager'
                ],
                'type'                          => 'coroutine',
            ],
            'Events'                        => [
                'class'                         => Events::class,
                'args'                          => [],
                'type'                          => 'coroutine',
            ],
            'Apm'                           => [
                //'class'                         => CoroutineProfiler::class,
                //'class'                         => Profiler::class,
                'class'                         => Profiler::class,
                'args'                          => [
                    'Backend'                       => 'ApmBackend',
                    //'worker_id'                     => [Kernel::class, 'get_worker_id'],
                    'profile_data_structure'        => Kernel::APM_DATA_STRUCTURE,
                ],
                'type'                          => 'coroutine',
            ],
            'ApmBackend'                    => [
                'class'                         => NullBackend::class,
                'args'                          => [],
            ],
            'Middlewares'                   => [
                'class'                         => Middlewares::class,
                'args'                          => [
                    'middlewares'                  => [],//these are defined in GuzabaPlatform
                ],
            ],
            'TimeCache'                     => [
                //'class'                         => SwooleTableIntCache::class,
                'class'                         => \Guzaba2\Cache\NullCache::class,
                'args'                          => [],
                'initialize_immediately'        => TRUE,
            ],
            'ContextCache'                  => [ //cache within the execution
                'class'                         => ContextCache::class,
                'args'                          => [],
            ],
            'WorkerCache'                   => [
                'class'                         => MemoryCache::class,
                'args'                          => [],
            ],
            'GlobalCache'                   => [
                //Swoole table is not suitable for general purpose cache, but only a specialized one as it needs to have specific structure defined
                //'class'                         => SwooleTableCache::class,
                //'args'                          => [],
                //'initialize_immediately'        => TRUE,
                'class'                         => RedisCache::class,
                'args'                          => [
                    'connection_class'              => RedisConnection::class,
                ],
            ],
            'FrontendRouter'                     => [
                'class'                         => VueRouter::class,
                'args'                          => [
                    'router_file'                   => './public_src/components_config/router.config.js',
                ],
            ],
            'TransactionManager'           => [
                'class'                         => TransactionManager::class,
                'args'                          => [],
                'type'                          => 'coroutine',
            ],

        ],
    ],
];