<?php


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Nosql\Redis\ConnectionCoroutine;

class RedisConnection extends ConnectionCoroutine
{
    protected const CONFIG_DEFAULTS = [
        'host'      => 'hostname.or.ip',
        'port'      => 6379,
        'timeout' => 1.5,
        'password' => '',
        'database' => 0,
        'options' => [
            // returns saved arrays properly
            'compatibility_mode' => true
        ],
        'expiry_time' => null
    ];

    protected const CONFIG_RUNTIME = [];
}