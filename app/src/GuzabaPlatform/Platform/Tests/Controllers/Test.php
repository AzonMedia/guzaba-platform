<?php

namespace GuzabaPlatform\Platform\Tests\Controllers;


use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Http\Method;
use Guzaba2\Mvc\Controller;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Application\MysqlConnection;

class Test extends Controller
{

    public const ROUTES = [
        GP::API_ROUTE_PREFIX.'/test'      => [
            Method::HTTP_GET_HEAD_OPT               => [self::class, 'main'],
        ],
    ];

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'ConnectionFactory',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main()
    {
        //$struct = ['message' => 'ok'];
        //$Response = self::get_structured_ok_response($struct);
        //$Connection = static::get_service('ConnectionFactory')->get_connection(MysqlConnection::class, $CR);
        //$Context = Coroutine::getContext();
        //print $Context->CurrentUser->is_new();
        $str = 'asd';
        $Response = self::get_string_ok_response($str);
        return $Response;
    }
}