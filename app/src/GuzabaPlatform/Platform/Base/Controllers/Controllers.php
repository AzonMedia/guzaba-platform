<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Base\Controllers;

use Guzaba2\Authorization\Role;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Http\Method;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Mvc\Controller;
use Guzaba2\Mvc\Interfaces\ControllerInterface;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

/**
 * Class Controllers
 * @package GuzabaPlatform\Platform\Base\Controllers
 * Provides basic controllers for retrieving controllers information
 */
class Controllers extends BaseController
{

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/base/controllers' => [
                Method::HTTP_GET => [self::class, 'get_controllers'],
            ],
            '/base/controllers/{controller_name}' => [
                Method::HTTP_GET => [self::class, 'get_controller_actions'],
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Returns all controllers (Guzaba2\Mvc\Interfaces\ControllerInterface).
     * If $role_uuid is provided only controllers that have at least one action that can be performed by the provided role will be returned.
     * @param string|null $role_uuid
     * @return ResponseInterface
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     * @throws \Guzaba2\Kernel\Exceptions\ConfigurationException
     */
    public function get_controllers(?string $role_uuid = NULL) : ResponseInterface
    {
        if ($role_uuid) {
            $Role = new Role($role_uuid);
            $controllers = Controller::get_controller_classes_role_can_perform($Role);
        } else {
            $controllers = Controller::get_controller_classes();
        }
        $controllers = array_values($controllers);
        sort($controllers);
        $struct = ['controllers' => $controllers];
        return self::get_structured_ok_response($struct);
    }

    /**
     * @param string $controller_name
     * @param string|null $role_uuid
     * @return ResponseInterface
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     * @throws \Guzaba2\Kernel\Exceptions\ConfigurationException
     */
    public function get_controller_actions(string $controller_name, ?string $role_uuid = NULL) : ResponseInterface
    {
        if (strpos($controller_name,'-')) {
            $controller_name = str_replace('-','\\',$controller_name);
        }
        if (!$controller_name) {
            throw new InvalidArgumentException(sprintf(t::_('No $controller_name provided. A controller class name is expected.')));
        }
        if (!class_exists($controller_name)) {
            //return self::get_structured_badrequest_response(['message' => sprintf(t::_('The provided controller class %1$s does not exist.'), $controller_name)]);
            throw new InvalidArgumentException(sprintf(t::_('The provided $controller_name class %1$s does not exist.'), $controller_name));
        }
        if (!is_a($controller_name, ControllerInterface::class, TRUE)) {
            throw new InvalidArgumentException(sprintf(t::_('The provided $controller_name class %1$s does not implement %2$s.'), $controller_name, ControllerInterface::class ));
        }
        if ($role_uuid) {
            $Role = new Role($role_uuid);
            $actions = $controller_name::get_actions_role_can_perform($Role);
        } else {
            $actions = $controller_name::get_actions();
        }

        $struct = ['actions' => $actions];
        return self::get_structured_ok_response($struct);
    }
}