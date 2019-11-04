<?php

use Azonmedia\Lock\Backends\SwooleTableBackend;
use Azonmedia\Lock\CoroutineLockManager;
use Guzaba2\Database\ConnectionFactory;
use Guzaba2\Database\ConnectionProviders\Basic;
use Guzaba2\Database\ConnectionProviders\Pool;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Orm\MetaStore\NullMetaStore;
use Guzaba2\Orm\MetaStore\SwooleTable;
use Guzaba2\Orm\Store\Memory;
use Guzaba2\Orm\Store\Nosql\Redis;
use Guzaba2\Orm\Store\NullStore;
use Guzaba2\Orm\Store\Sql\Mysql;
use GuzabaPlatform\Platform\Application\RedisConnection;

return [
    \GuzabaPlatform\Platform\Application\GuzabaPlatform::class => [
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
    ],
    \GuzabaPlatform\Platform\Application\MysqlConnection::class => [
        'host'      => '192.168.0.22',
        'port'      => 3306,
        'user'      => 'root',
        'password'  => 'rectoverso',
        'database'  => 'guzaba2',
        'tprefix'   => 'guzaba_',
    ],
    RedisConnection::class => [
        'host'      => 'redis',
        'port'      => 6379,
        'timeout' => 1.5,
        'password' => '',
        'database' => 0,
        'options' => [
            // returns saved arrays properly
            'compatibility_mode' => true
        ],
        'expiry_time' => null
    ],
    \Guzaba2\Di\Container::class => [
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
//        'SomeExample'                   => [
//            'class'                         => SomeClass::class,
//            'args'                          => [
//                'arg1'                      => 20,
//                'arg2'                      => 'something'
//            ],
//        ]
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
                    'connection_class'              => \GuzabaPlatform\Platform\Application\MysqlConnection::class,
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
            'QueryCache' => [
                'class'                         => \Guzaba2\Database\QueryCache::class,
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
        ],
    ],
];