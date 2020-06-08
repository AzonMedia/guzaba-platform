<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Models;

use Azonmedia\Patterns\Traits\ReadonlyOverloadingTrait;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Translator\Translator as t;

class Component extends Base
{

    use ReadonlyOverloadingTrait;

    protected array $data = [
        'package_name'      => '',
        'name'              => '',
        'description'       => '',
        'namespace'         => '',

        //the below are used only on installed components
        'root_dir'          => '',
        'src_dir'           => '',
        'public_src_dir'    => '',
        'installed_time'    => 0,
    ];

    /**
     * Component constructor.
     * @param string $name The name of the component in packagist.org
     * @param string $namespace
     * @param string $root_dir
     * @param string $src_dir
     * @param string $public_src_dir
     * @param int $installed_time
     * @throws InvalidArgumentException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     */
    public function __construct(string $package_name, string $name = '', string $description = '', string $namespace = '', string $root_dir = '', string $src_dir = '', string $public_src_dir = '', int $installed_time = 0)
    {

        if (!$package_name) {
            throw new InvalidArgumentException(sprintf(t::_('No component package name provided.')));
        }
        //add better name validation
        if (strpos($package_name, '/') === FALSE) {
            throw new InvalidArgumentException(sprintf(t::_('The provided component package name %s does not contain /.'), $package_name));
        }
        //TODO - cant start with / cant end with / and cant have more than one /

        if (!$name) {

        }

        if (!$namespace) {

        }

        foreach ($this->data as $key=>$value) {
            if (isset(${$key})) {
                $this->data[$key] = ${$key};
            }
        }
    }
}