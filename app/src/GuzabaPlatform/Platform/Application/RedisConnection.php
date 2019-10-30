<?php


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Nosql\Redis\ConnectionCoroutine;

class RedisConnection extends ConnectionCoroutine
{
    protected const CONFIG_DEFAULTS = [
        'host'      => 'redis',
        'port'      => 6379,
        'timeout' => 1.5,
        'password' => '',
        'database' => 0,

    ];

    protected const CONFIG_RUNTIME = [];
}