<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Base\Controllers;


use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Http\Method;
use Guzaba2\Kernel\Kernel;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\Interfaces\ModelInterface;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Translator\Translator as t;

class Models extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/base/models' => [
                Method::HTTP_GET => [self::class, 'get_classes'],
            ],
            '/base/models/{class_name}' => [
                Method::HTTP_GET => [self::class, 'get_objects'],
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Returns all classes that implement the ModelInterface
     * @return ResponseInterface
     */
    public function get_classes() : ResponseInterface
    {
        $classes = array_values(Kernel::get_classes([], ModelInterface::class));
        $struct = [ 'models' => $classes ];
        return self::get_structured_ok_response($struct);
    }

    /**
     * Returns all objects from $class_name
     * @param string $class_name
     * @return ResponseInterface
     */
    public function get_objects(string $class_name) : ResponseInterface
    {
        if (strpos($class_name,'-')) {
            $class_name = str_replace('-','\\',$class_name);
        }
        if (!$class_name) {
            throw new InvalidArgumentException(sprintf(t::_('No $class_name provided.')));
        }
        if (!class_exists($class_name)) {
            throw new InvalidArgumentException(sprintf(t::_('The $class_name argument contains a class %1s that does not exist.'), $class_name));
        }
        if (!is_a($class_name, ModelInterface::class, TRUE)) {
            throw new InvalidArgumentException(sprintf(t::_('The $class_name argument contains a class %1s that does not implement %2s.'), $class_name, ModelInterface::class ));
        }
        $data = $class_name::get_data_by([], 0, 0, FALSE, $class_name::get_object_name_property() );
        $struct = [ 'objects' => $data ];
        return self::get_structured_ok_response($struct);
    }
}