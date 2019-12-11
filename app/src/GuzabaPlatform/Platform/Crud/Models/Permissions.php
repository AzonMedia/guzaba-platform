<?php

namespace GuzabaPlatform\Platform\Crud\Models;

use Guzaba2\Base\Base;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Orm\ActiveRecord;
use Azonmedia\Reflection\ReflectionClass;

class Permissions extends Base
{
    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'ConnectionFactory',
        ]
    ];

    protected const CONFIG_RUNTIME = [];

	public static function get_controllers_tree()
	{		

		$tree = [];
		$classes = [];
        // get all ActiveRecord classes that are loaded by the Kernel
        $controllers = ActiveRecord::get_active_record_classes(array_keys(Kernel::get_registered_autoloader_paths()));

        foreach ($controllers as $class_name) {
            $RClass = new ReflectionClass($class_name);

            if ($RClass->isInstantiable() && $RClass->extendsClass('Guzaba2\Mvc\Controller')) {
                $classes[$class_name] = $class_name;
            }
        }

        $tree = self::explodeTree($classes, "\\");

        return $tree;
	}

	private static function explodeTree($array, $delimiter = '\\', $baseval = false)
	{
		if(!is_array($array)) return false;

		$splitRE   = '/' . preg_quote($delimiter, '/') . '/';

		$returnArr = array();

		foreach ($array as $key => $val) {
			// Get parent parts and the current leaf
			$parts	= preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
			$leafPart = array_pop($parts);

			// Build parent structure
			// Might be slow for really deep and large structures
			$parentArr = &$returnArr;

			foreach ($parts as $part) {
				if (!isset($parentArr[$part])) {
					$parentArr[$part] = array();
				} elseif (!is_array($parentArr[$part])) {
					if ($baseval) {
						$parentArr[$part] = array('__base_val' => $parentArr[$part]);
					} else {
						$parentArr[$part] = array();
					}
				}
				$parentArr = &$parentArr[$part];
			}

			// Add the final part to the structure
			if (empty($parentArr[$leafPart])) {
            	$RClass = new ReflectionClass($val);

                $methods_arr = [];
                foreach ($RClass->getMethods() as $method) {
                    if ($method->class == $val && substr($method->name, 0, 1 ) !== "_" ) {
                        $methods_arr[$method->name] = $val . "::" . $method->name;
                    }
                }  

				$parentArr[$leafPart] = $methods_arr;
			} elseif ($baseval && is_array($parentArr[$leafPart])) {
				$parentArr[$leafPart]['__base_val'] = $val;
			}
		}
		return $returnArr;
	}

	public static function get_permissions(string $class_name, string $action_name)
	{
		$Connection = Kernel::get_service('ConnectionFactory')->get_connection('GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine', $ScopeReference);

		$q = "
SELECT 
 	roles.role_name
 	-- , 
 	-- acl_permissions.*
FROM
	{$Connection::get_tprefix()}acl_permissions as acl_permissions
LEFT JOIN
	{$Connection::get_tprefix()}roles as roles
	ON
 		roles.role_id = acl_permissions.role_id
WHERE
	acl_permissions.class_name = :class_name
AND
	acl_permissions.action_name = :action_name
ORDER BY
	roles.role_name
 ";

 		$data = $Connection->prepare($q)->execute(['class_name' => $class_name, 'action_name' => $action_name])->fetchAll(); 		
        return $data;
	}
}