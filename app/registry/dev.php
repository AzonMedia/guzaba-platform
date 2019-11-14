<?php

use Azonmedia\Apm\CoroutineProfiler;
use Azonmedia\Apm\NullBackend;
use Azonmedia\Lock\Backends\SwooleTableBackend;
use Azonmedia\Lock\CoroutineLockManager;
use Guzaba2\Authorization\CurrentUser;
//use Guzaba2\Authorization\User;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Guzaba2\Database\ConnectionFactory;
use Guzaba2\Database\ConnectionProviders\Basic;
use Guzaba2\Database\ConnectionProviders\Pool;
use Guzaba2\Database\QueryCache;
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
use Guzaba2\Orm\BlockingStore\Nosql\MongoDB;
use GuzabaPlatform\Platform\Application\MongoDbConnection;
use Guzaba2\Authorization\BypassAuthorizationProvider;
use Psr\Log\LogLevel;

return [
    GuzabaPlatform::class => [
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
        'log_level'     => LogLevel::DEBUG,
        'override_html_content_type' => 'json',//to facilitate debugging when opening the XHR in browser
    ],
    Container::class => [
        'dependencies' => [
            'ConnectionFactory'             => [
                'class'                         => ConnectionFactory::class,
                'args'                          => [
                    'ConnectionProvider'            => 'ConnectionProviderPool',
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

            'OrmStore'                      => [
                'class'                         => Memory::class,//the Memory store is the first to be looked into
                'args'                          => [
                    'FallbackStore'                 => 'RedisOrmStore',
                ],
            ],
            'RedisOrmStore'                 => [
                'class'                         => Redis::class,
                'args'                          => [
                    'FallbackStore'                 => 'MysqlOrmStore',
                    'connection_class'              => RedisConnection::class,
                ],
            ],
            'RedisCo'                       => [
                'class'                         => RedisConnection::class,
                'args'                          => [
                ],
            ],
            'MysqlOrmStore'                 => [
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
            'OrmMetaStore'                  => [
                'class'                         => SwooleTable::class,
                'args'                          => [
                    'FallbackMetaStore'             => 'NullOrmMetaStore',
                ],
                'initialize_immediately'        => TRUE,
            ],
            'NullOrmMetaStore'              => [
                'class'                         => NullMetaStore::class,
                'args'                          => [
                    'FallbackStore'                 => NULL,
                ],
            ],
            'BlankOrmStore'                 => [
                'class'                         => BlankOrmStore::class,
                'args'                          => [],
            ],
            'QueryCache' => [
                'class'                         => QueryCache::class,
                'args'                          => [
                    // TODO add required params
                ],
            ],
            'LockManager'                   => [
                'class'                         => CoroutineLockManager::class,
                'args'                          => [
                    'Backend'                       => 'LockManagerBackend',
                    'Logger'                        => [Kernel::class, 'get_logger'],
                ],
                'initialize_immediately'        => TRUE,
            ],
            'LockManagerBackend'            => [
                'class'                         => SwooleTableBackend::class,
                'args'                          => [
                    'Logger'                        => [Kernel::class, 'get_logger'],
                ],
            ],
            'AuthorizationProvider'         => [
                'class'                         => BypassAuthorizationProvider::class,
                'args'                          => [],
            ],
            'CurrentUser'                   => [
                'class'                         => CurrentUser::class,
                'args'                          => [
                    'User'                          => 'DefaultCurrentUser',
                ],
            ],
            'DefaultCurrentUser'            => [
                'class'                         => User::class,
                'args'                          => [
                    'index'                         => 0,
                ],
            ],
            'Events'                        => [
                'class'                         => Events::class,
                'args'                          => [],
            ],
            'Apm'                           => [
                'class'                         => CoroutineProfiler::class,
                'args'                          => [
                    'Backend'                       => 'ApmBackend',
                    'worker_id'                     => [Kernel::class, 'get_worker_id'],
                ],
            ],
            'ApmBackend'                    => [
                'class'                         => NullBackend::class,
                'args'                          => [],
            ]
        ],
    ],
];