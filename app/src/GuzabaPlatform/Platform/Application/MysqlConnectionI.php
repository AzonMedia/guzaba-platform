<?php


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Sql\Mysql\ConnectionMysqli;

class MysqlConnectionI extends ConnectionMysqli
{

    protected const CONFIG_DEFAULTS = [
        'host'      => '192.168.0.92',
        'port'      => 3306,
        'username'  => 'vesko',
        'password'  => 'impas560',
        'database'  => 'guzaba2',
        'services'      => [
            'ConnectionFactory'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function is_connected() : bool
    {
        return $this->Mysqli->ping();
    }

    public function close() : void
    {
        $this->Mysqli->close();
    }
}