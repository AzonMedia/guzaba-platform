<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Database\Nosql\MongoDb\ConnectionCoroutine;

class MongoDbConnection extends ConnectionCoroutine
{
    protected const CONFIG_DEFAULTS = [
        'host'      => '192.168.0.95',
        'port'      => 27017,
        'database' 	=> 'swoole',
        'username' 	=> 'swoole_user',
        'password' 	=> 'swoole_password',
        'tprefix' 	=> 'guzaba_',
    ];

    protected const CONFIG_RUNTIME = [];
}