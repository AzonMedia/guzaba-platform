<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Models;

use Azonmedia\Utilities\HttpUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use Guzaba2\Translator\Translator as t;

//not used
class Store extends Base
{

    //public string $store_name;
    //public string $store_url;
    //public string $store_added_time;

    public const COMPONENTS_FILE = '/components.json';
    public const LOGO_FILE = 'store_logo.png';//must be a square image
    public const ICON_FILE = 'store_icon.ico';//must be a square image

    protected array $data = [
        'store_name'        => '',
        'store_url'         => '',
        'store_added_time'  => 0,
    ];

    /**
     * Store constructor.
     * Does a lookup in manifest.json
     * @param string $store_name
     */
    public function __construct(string $store_name, string $store_url, int $store_added_time)
    {
        parent::__construct();

        if (!$store_name) {
            throw new InvalidArgumentException(sprintf(t::_('No store name provided.')));
        }
        self::validate_store_url($store_url);

        //$this->store_name = $store_name;
        //$this->store_url = $store_url;
        //$this->store_added_time = $store_added_time;
        $this->data['store_name'] = $store_name;
        $this->data['store_url'] = $store_url;
        $this->data['store_added_time'] = $store_added_time;

        //do not actually load the URL - just creating the object should not trigger external requests
        //the URL will be accessed only if the store is added to the Manifest

    }

    public static function validate_store_url(string $store_url, bool $validate_components_file = FALSE): bool
    {
        if (!$store_url) {
            throw new InvalidArgumentException(sprintf(t::_('No store URL provided.')));
        } elseif (!filter_var($store_url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf(t::_('The provided store URL %s is not a valid URL.'), $store_url));
        }
        if ($validate_components_file) {
            if (!HttpUtil::resource_exists($store_url)) {
                throw new InvalidArgumentException(sprintf(t::_('The provided Store URL %s does not seem to be valid (the HTTP reponse code is not OK 200).'), $store_url));
            }
            $components_url = $store_url.self::COMPONENTS_FILE;
            if (!HttpUtil::resource_exists($components_url)) {
                throw new InvalidArgumentException(sprintf(t::_('The components.json file for Store URL %s does not seem to exist (the HTTP reponse code is not OK 200).'), $components_url));
            }
        }
    }

    public static function get_instance_from_url(string $store_url): self
    {
        self::validate_store_url($store_url, TRUE);
        $ComponentsDecoded = json_decode(file_get_contents($store_url.self::COMPONENTS_FILE), FALSE, 512, JSON_THROW_ON_ERROR);
        return new Store($store_url, $ComponentsDecoded->store_name, 0);
    }

    public function __get(string $property) /* mixed */
    {
        if (!array_key_exists($property, $this->data)) {
            throw new RunTimeException(sprintf(t::_('The objects of class %s do not have a property %s.'), get_class($this), $property));
        }
        return $this->data[$property];
    }

    public function __set(string $property, /* mixed */ $value) : void
    {
//        if (!array_key_exists($property, $this->data)) {
//            throw new RunTimeException(sprintf(t::_('The objects of class %s do not have a property %s.'), get_class($this), $property));
//        }
//        $this->data[$property] = $value;
        throw new RunTimeException(sprintf(t::_('It is not allowed to change the properties on objects of class %s.'), get_class($this) ));
    }

    public function __isset(string $property): bool
    {
        return array_key_exists($property, $this->data);
    }

    public function __unset(string $property): void
    {
        throw new RunTimeException(sprintf(t::_('It is not allowed to unset properties on objects of class %s.'), get_class($this) ));
    }

    public function get_data(): array
    {
        return $this->data;
    }

}