<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Sql\Mysql\ConnectionCoroutine;
use GuzabaPlatform\Platform\Application\Traits\ConnectionConstructorTrait;

class MysqlConnectionCoroutine extends ConnectionCoroutine
{

    protected const CONFIG_DEFAULTS = [
        'host'          => 'hostname.or.ip',
        'port'          => 3306,
        'user'          => 'user',
        'password'      => 'pass',
        'database'      => 'db',
        'tprefix'       => 'prefix_',
        'strict_type'   => TRUE,
        'fetch_mode'    => FALSE,
    ];

    protected const CONFIG_RUNTIME = [];

    use ConnectionConstructorTrait;

}