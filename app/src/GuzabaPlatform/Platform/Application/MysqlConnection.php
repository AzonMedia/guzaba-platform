<?php

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Sql\Mysql\ConnectionCoroutine;

class MysqlConnection extends ConnectionCoroutine
{

    protected const CONFIG_DEFAULTS = [
        'host'      => '192.168.0.22',
        'port'      => 3306,
        'user'      => 'root',
        'password'  => 'rectoverso',
        'database'  => 'guzaba2',
        'tprefix'   => 'guzaba_',
    ];

    //protected static $CONFIG_RUNTIME = [];
    protected const CONFIG_RUNTIME = [];

    public function __construct()
    {
        parent::__construct();

        //parent::__construct(self::$CONFIG_RUNTIME);
        //print 'NEW CONNECTION'.PHP_EOL;
    }
}