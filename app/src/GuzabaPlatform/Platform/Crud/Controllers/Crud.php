<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Crud\Controllers;

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
use Azonmedia\Reflection\ReflectionClass;

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

    /**
     * returns a list with all ActiveRecord classes
     */
    public function classes(): ResponseInterface
    {
        $struct['classes'] = \GuzabaPlatform\Platform\Crud\Models\Crud::get_tree();
        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    /**
     * @param string $class_name
     * @param int $page
     * @param int $limit
     * @param string $search_values: url encoded, base64 encoded, JSON.stringified array
     * @param string $sort_by: column name
     * @param string $sort_desc: true / false; if true => sort DESC
     */
    public function objects(string $class_name, int $page, int $limit, string $search_values, string $sort_by, string $sort_desc): ResponseInterface
    {
        $struct = [];

        if ($sort_by == 'none') {
            $sort_by = NULL;
        }

        $class_name = str_replace(".", "\\", $class_name);
        $ActiveRecord = new $class_name(0);

        $offset = ($page - 1) * $limit;
        $search = json_decode(base64_decode(urldecode($search_values)));

        $columns_data = $ActiveRecord::get_columns_data();
        $activeRecordKeys = array_keys($columns_data);
        $activeRecordKeys[] = 'meta_object_uuid';
        $struct['properties'] = $activeRecordKeys;

        $struct['data'] = $ActiveRecord::get_data_by((array) $search, $offset, $limit, $use_like = TRUE, $sort_by, (bool) $sort_desc, $total_found_rows);

        $struct['totalItems'] = $total_found_rows;
        $struct['numPages'] = ceil($struct['totalItems'] / $limit);

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }
}
