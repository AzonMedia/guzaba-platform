<?php

// Example local registry config
// rename to local.php to use
// can hold sensitive environment dependent data that won't be pushed to git

return [
    \GuzabaPlatform\Platform\Application\RedisConnection::class => [
        'host'      => 'redis',
        'port'      => 6379,
        'timeout' => 1.5,
        'password' => '',
        'database' => 0,
        'expiry_time' => 3600,
    ],
    \GuzabaPlatform\Platform\Application\MysqlConnection::class => [
        'host'      => 'guzabaplatformdev_mysqlhost_1',
        'port'      => 3306,
        'user'      => 'root',
        'password'  => 'somerootpass',
        'database'  => 'newwealthcapital',
        'tprefix'   => 'guzaba_',
    ],
    \GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine::class => [
        'host'      => 'guzabaplatformdev_mysqlhost_1',
        'port'      => 3306,
        'user'      => 'root',
        'password'  => 'somerootpass',
        'database'  => 'newwealthcapital',
        'tprefix'   => 'guzaba_',
    ],
    \GuzabaPlatform\Platform\Application\MongoDbConnection::class => [
        'host'      => 'host',
        'port'      => 27017,
        'database' 	=> 'db',
        'username' 	=> 'user',
        'password' 	=> 'pass',
        'AI_table'  => 'guzaba_autoincrement_counters',
    ],

];