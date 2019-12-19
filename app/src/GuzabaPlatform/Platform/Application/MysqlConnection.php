<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Sql\Mysql\ConnectionMysqli;

class MysqlConnection extends ConnectionMysqli
{

    protected const CONFIG_DEFAULTS = [
        'host'      => 'hostname.or.ip',
        'port'      => 3306,
        'user'      => 'user',
        'password'  => 'pass',
        'database'  => 'db',
        'tprefix'   => 'prefix_',
    ];

    protected const CONFIG_RUNTIME = [];

    public function __construct()
    {
        $options = array_filter(self::CONFIG_RUNTIME, fn(string $key) : bool => in_array($key, self::SUPPORTED_OPTIONS), ARRAY_FILTER_USE_KEY );
        parent::__construct($options);
    }

}