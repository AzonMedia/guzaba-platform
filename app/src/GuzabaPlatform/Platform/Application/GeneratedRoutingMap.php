<?php


namespace GuzabaPlatform\Platform\Application;

use Azonmedia\Reflection\ReflectionClass;
use Azonmedia\Routing\RoutingMapArray;
use Guzaba2\Http\Method;
use Symfony\Component\VarExporter\VarExporter;

class GeneratedRoutingMap extends RoutingMapArray
{


    public function __construct(iterable $routing_map, string $dump_dir)
    {
        parent::__construct($routing_map);

        $routing_map_with_constants = [];

        foreach ($routing_map as $route=>$data) {
            foreach ($data as $method=>$controller) {
                if (is_array($controller)) {
                    $controller = $controller[0].'::'.$controller[1];//for nicer view
                }
                $routing_map_with_constants[$route][implode(', ', array_values(Method::get_methods($method)))] = $controller;
            }
        }

        $file_path = $dump_dir.'/routing_map.php';

        //$routing_map_str = var_export($routing_map_with_constants,TRUE);
        $routing_map_str = VarExporter::export($routing_map_with_constants);//nicer

        $file_content = <<<FILE
<?php
declare(strict_types=1);

\$routing_map = $routing_map_str; 

return \$routing_map;
FILE;

        if (\Swoole\Coroutine::getCid() > 0) {
            \Swoole\Coroutine\System::writeFile($file_path, $file_content );//overwrite the file on each launch
        } else {
            file_put_contents($file_path, $file_content);
        }

    }

}