<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Nosql\Redis\ConnectionCoroutine;

/**
 * Class RedisConnection
 * @package GuzabaPlatform\Platform\Application
 * A coroutine connection for redis.
 */
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
            'compatibility_mode' => TRUE
        ],
        'expiry_time' => NULL
    ];

    protected const CONFIG_RUNTIME = [];

    public function __construct()
    {
        $options = array_filter(self::CONFIG_RUNTIME, fn(string $key) : bool => in_array($key, self::SUPPORTED_OPTIONS), ARRAY_FILTER_USE_KEY );
        parent::__construct($options);
    }
}