<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Sql\Mysql\ConnectionCoroutine;

class MysqlConnectionCoroutine extends ConnectionCoroutine
{

    protected const CONFIG_DEFAULTS = [
        'host'      => 'host',
        'port'      => 27017,
        'database' 	=> 'db',
        'username' 	=> 'user',
        'password' 	=> 'pass',
        'AI_table'  => 'guzaba_autoincrement_counters',
    ];

    protected const CONFIG_RUNTIME = [];

    public function __construct()
    {
        $options = array_filter(self::CONFIG_RUNTIME, fn(string $key) : bool => in_array($key, self::SUPPORTED_OPTIONS), ARRAY_FILTER_USE_KEY );
        parent::__construct($options);
    }

}