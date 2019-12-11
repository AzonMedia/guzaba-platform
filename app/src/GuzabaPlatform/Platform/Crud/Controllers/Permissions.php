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
    ];

    /**
     * returns a list with all ActiveRecord classes
     */
    public function controllers(): ResponseInterface
    {
        $struct['tree'] = [];

        $tree = \GuzabaPlatform\Platform\Crud\Models\Permissions::get_controllers_tree();

        $struct['tree'] = [
            'Controllers' => $tree,
            'Non-Controllers' => ''
        ];

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
    public function permissions(string $method_name): ResponseInterface
    {
        $struct = [];

        list($class_name, $action_name) = explode("::", $method_name);

        try {
            // $permissions = \Guzaba2\Authorization\Acl\Permission::get_by([
            //     'class_name' => str_replace("/", "\\", $class_name),
            //     'action_name' => $action_name
            // ]);

            // if (count($permissions)) {
            //     $ActiveRecord = $permissions[0];

            // }
            $permissions = \GuzabaPlatform\Platform\Crud\Models\Permissions::get_permissions(str_replace("/", "\\", $class_name), $action_name);
            print_r($permissions);
            $struct['items'] = $pemissions;
            $struct['message'] = "Class: " . $class_name . ", action: " . $action_name;
        } catch (RecordNotFoundException $exception) {

        }

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }
}
