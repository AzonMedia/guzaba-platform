<?php

namespace GuzabaPlatform\Platform\Home\Controllers;

use Guzaba2\Http\Method;
use Guzaba2\Mvc\Controller;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Kernel\Kernel;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;

class Crud extends Controller
{

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'ConnectionFactory'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public const ROUTES = [
        GP::API_ROUTE_PREFIX . '/crud-classes' => [
            Method::HTTP_GET_HEAD_OPT => [self::class, 'classes']
        ],
        GP::API_ROUTE_PREFIX . '/crud-objects/{class_name}/{page}/{limit}/{search_values}/{sort_by}/{sort_desc}' => [
            Method::HTTP_GET_HEAD_OPT => [self::class, 'objects']
        ],
    ];

    public function classes(): ResponseInterface
    {
        $struct['classes'] = [];

        $classes = ActiveRecord::get_active_record_classes(array_keys(Kernel::get_registered_autoloader_paths()));
        foreach ($classes as $class_name) {
            try {
                $ActiveRecord = new $class_name(0);
                $struct['classes'][] = $class_name;
            } catch (RunTimeException $exception){
                // do nothing; 
            }
        }

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    public function objects(string $class_name, int $page, int $limit, string $search_values, string $sort_by, string $sort_desc): ResponseInterface
    {
        $Connection = static::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR);

        $struct = [];

        $class_name = str_replace(".", "\\", $class_name);
        $ActiveRecord = new $class_name(0);
        $main_table = $Connection::get_tprefix().$ActiveRecord::get_main_table();

        $offset = ($page - 1) * $limit;
        $search = json_decode(base64_decode(urldecode($search_values)));

        $columns_data = $ActiveRecord::get_columns_data();
        $activeRecordKeys = array_keys($columns_data);
        $activeRecordKeys[] = 'object_uuid';
        $struct['properties'] = $activeRecordKeys;
        
        $struct['data'] = $ActiveRecord::get_data_by((array) $search, $offset, $limit, $use_like = TRUE, $sort_by, (bool) $sort_desc);

        $struct['totalItems'] = $ActiveRecord::get_data_count_by((array) $search, $use_like = TRUE);
        $struct['numPages'] = ceil($struct['totalItems'] / $limit);

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }
}
