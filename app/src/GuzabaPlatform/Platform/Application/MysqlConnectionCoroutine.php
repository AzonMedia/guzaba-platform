<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Sql\Mysql\ConnectionCoroutine;

class MysqlConnectionCoroutine extends ConnectionCoroutine
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

}