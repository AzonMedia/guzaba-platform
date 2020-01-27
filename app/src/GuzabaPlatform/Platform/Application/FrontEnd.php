<?php


namespace GuzabaPlatform\Platform\Application;


use Guzaba2\Base\Base;

class FrontEnd extends Base
{
    public static function add_vue_route(array $route) : void
    {
        //write to components_config/routes.runtime.js
    }

    public static function add_webpack_path(string $path) : void
    {
        //"@alias": "/absolute/path" .. no need of resolve
    }
}