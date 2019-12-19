<?php

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

class Permissions extends Controller
{

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'ConnectionFactory'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public const ROUTES = [
        GP::API_ROUTE_PREFIX . '/permissions-controllers' => [
            Method::HTTP_GET_HEAD_OPT => [self::class, 'controllers']
        ],
        GP::API_ROUTE_PREFIX . '/permissions-users/{method_name}' => [
            Method::HTTP_GET_HEAD_OPT => [self::class, 'permissions']
        ],
        GP::API_ROUTE_PREFIX . '/permissions-objects/{class_name}/{object_uuid}' => [
            Method::HTTP_GET_HEAD_OPT => [self::class, 'permissions_object']
        ],
    ];

    /**
     * returns a list with all ActiveRecord classes
     */
    public function controllers(): ResponseInterface
    {
        $struct['tree'] = [];

        $classes = \GuzabaPlatform\Platform\Crud\Models\Permissions::get_tree();

        $struct['tree'] = [
            'Controllers' => $classes[0],
            'Non-Controllers' => $classes[1]
        ];

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    /**
     * @param string $method_name
     */
    public function permissions(string $method_name): ResponseInterface
    {
        $struct = [];

        list($class_name, $action_name) = explode("::", $method_name);
        $class_name = str_replace(".", "\\", $class_name);

        $struct['items'] = \GuzabaPlatform\Platform\Crud\Models\Permissions::get_permissions($class_name, $action_name);

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    /**
     * @param string $class_name
     * @param string $object_uuid
     */
    public function permissions_object(string $class_name, string $object_uuid): ResponseInterface
    {
        $struct = [];

        $class_name = str_replace(".", "\\", $class_name);

        $ActiveRecord = new $class_name($object_uuid);
        $struct['items'] = \GuzabaPlatform\Platform\Crud\Models\Permissions::get_permissions_by_uuid($class_name, $ActiveRecord->meta_object_id);

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }
}
