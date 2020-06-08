<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Models;

use Azonmedia\Patterns\Traits\ReadonlyOverloadingTrait;
use Azonmedia\Utilities\HttpUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use Guzaba2\Translator\Translator as t;

class Store extends Base
{

    use ReadonlyOverloadingTrait;

    public const COMPONENTS_FILE = '/components.json';
    public const LOGO_FILE = '/store_logo.png';//must be a square image
    public const ICON_FILE = '/store_icon.ico';//must be a square image

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

        if ($store_url[-1] === '/') {
            $store_url = substr($store_url, 0, - 1);
        }

        $this->data['store_name'] = $store_name;
        $this->data['store_url'] = $store_url;
        $this->data['store_added_time'] = $store_added_time;

        //do not actually load the URL - just creating the object should not trigger external requests
        //the URL will be accessed only if the store is added to the Manifest

    }

    public static function validate_store_url(string $store_url, bool $validate_components_file = FALSE): void
    {
        if (!$store_url) {
            throw new InvalidArgumentException(sprintf(t::_('No Store URL provided.')));
        } elseif (!filter_var($store_url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf(t::_('The provided Store URL %s is not a valid URL.'), $store_url));
        }
        $url_components = parse_url($store_url);
        if (isset($url_components['query'])) {
            throw new InvalidArgumentException(sprintf(t::_('The provided Store URL %s has query component %s. The Store URL can not contain query component and must end with path.'), $store_url, $url_components['query'] ));
        }
        if (isset($url_components['fragment'])) {
            throw new InvalidArgumentException(sprintf(t::_('The provided Store URL %s has fragment component %s. The Store URL can not contain fragment component and must end with path.'), $store_url, $url_components['query'] ));
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
        return new Store($ComponentsDecoded->store_name, $store_url, 0);
    }

    public static function get_instance_from_manifest(string $store_url): self
    {
        $Manifest = Manifest::get_default_instance();
        return $Manifest->get_store_by_url($store_url);
    }

    public function get_decoded_object(): object
    {
        return json_decode(file_get_contents($this->store_url.self::COMPONENTS_FILE), FALSE, 512, \JSON_THROW_ON_ERROR);
    }

    /**
     * @return Component[]
     */
    public function get_available_components(): array
    {
        $ret = [];
        $DecodedObject = $this->get_decoded_object();
        foreach ($DecodedObject->components as $DecodedComponent) {
            $ret[] = new Component(
                $DecodedComponent->composer_package,//package_name
                $DecodedComponent->name,//name
                $DecodedComponent->description,//description
                $DecodedComponent->namespace,
                $DecodedComponent->root_dir,
                $DecodedComponent->src_dir,
                $DecodedComponent->public_sr_dir,
                $DecodedComponent->installed_time,
                );
        }

    }



}