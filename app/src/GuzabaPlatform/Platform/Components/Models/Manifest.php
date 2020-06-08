<?php


namespace GuzabaPlatform\Platform\Components\Models;


use Azonmedia\Utilities\HttpUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\LogicException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\Exceptions\ValidationFailedException;
use Guzaba2\Translator\Translator as t;

/**
 * Class Manifest
 * @package GuzabaPlatform\Platform\Components\Models
 * Manages manifest.json
 * To retrieve an instance for the main manifest.json @see Manifest::get_default_instance()
 */
class Manifest extends Base
{


    protected const CONFIG_DEFAULTS = [
        'services' => [
            'GuzabaPlatform',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * The path to the manifest file
     * @var string
     */
    protected string $path;

    public function __construct(string $manifest_path)
    {
        if (!file_exists($manifest_path)) {
            throw new InvalidArgumentException(sprintf(t::_('The provided path %s does not exist.'), $manifest_path));
        }
        $this->path = $manifest_path;

        //parse it to make sure it is valid
        //do not assign it to a property as if this property gets exported then it may produce unwanted effects
        json_decode(file_get_contents($this->path), FALSE, 512, JSON_THROW_ON_ERROR);
    }

    public function get_components(): array
    {
        $ret = [];
        
    }

    public function get_path(): string
    {
        return $this->path;
    }

    public static function get_default_instance(): self
    {
        /** @var GuzabaPlatform $GuzabaPlatform */
        $GuzabaPlatform = self::get_service('GuzabaPlatform');
        $manifest_path = $GuzabaPlatform->get_manifest_file_path();
        return new static($manifest_path);
    }

    public function get_decoded_object(): object
    {
        return json_decode(file_get_contents($this->path), FALSE, 512, \JSON_THROW_ON_ERROR);
    }

    public function update_manifest_file(\stdClass $DecodedObject): void
    {
        file_put_contents($this->path, json_encode($DecodedObject, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ));
    }

    public function add_store(Store $Store): void
    {
        if (!$Store->store_name) {
            throw new InvalidArgumentException(sprintf(t::_('The provided store has no name set.')));
        }
        $Store::validate_store_url($Store->store_url, TRUE);
        if ($this->has_store($Store)) {
            throw new ValidationFailedException(NULL, 'store', sprintf(t::_('The Store %s with URL %s is already added to %s.'), $Store->store_name, $Store->store_url, $this->path) );
        }
        $DecodedObject = $this->get_decoded_object();
        if (!isset($DecodedObject->stores)) {
            $DecodedObject->stores = [];
        }
        $DecodedObject->stores[] = ['name' => $Store->store_name, 'url' => $Store->store_url, 'added_time' => time() ];
        $this->update_manifest_file($DecodedObject);
    }

    public function remove_store(Store $Store): void
    {
        $DecodedObject = $this->get_decoded_object();
        if (!isset($DecodedObject->stores)) {
            $DecodedObject->stores = [];
        }
        if (!$this->has_store($Store)) {
            throw new ValidationFailedException(NULL, 'store', sprintf(t::_('The Store %s with URL %s can not be removed as it is not present in the %s file.'), $Store->store_name, $Store->store_url, ));
        }
        $stores = $DecodedObject->stores;
        $updated_stores = [];
        foreach ($stores as $DecodedStore) {
            if ($DecodedStore->url !== $Store->store_url) {
                $updated_stores[] = $DecodedStore;
            }
        }
        $DecodedObject->stores = $updated_stores;
        $this->update_manifest_file($DecodedObject);
    }

    public function has_store(Store $Store): bool
    {
        return $this->has_store_by_url($Store->store_url);
    }

    public function has_store_by_url(string $store_url): bool
    {
        $ret = FALSE;
        Store::validate_store_url($store_url, FALSE);
        $DecodedObject = $this->get_decoded_object();
        if (!isset($DecodedObject->stores)) {
            $DecodedObject->stores = [];
        }
        foreach ($DecodedObject->stores as $DecodedStore) {
            if ($DecodedStore->url === $store_url) {
                $ret = TRUE;
                break;
            }
        }
        return $ret;
    }

    public function get_store_by_url(string $store_url): Store
    {
        if (!$this->has_store_by_url($store_url)) {
            throw new RecordNotFoundException(sprintf(t::_('The file %s has no store with URL %s added.'), $Manifest->get_path(), $store_url));
        }
        $Store = NULL;
        $DecodedObject = $this->get_decoded_object();
        foreach ($DecodedObject->stores as $DecodedStore) {
            if ($DecodedStore->url === $store_url) {
                $Store = new Store($DecodedStore->name, $DecodedStore->url, $DecodedStore->added_time);
                break;
            }
        }
        if (!$Store) {
            throw new LogicException(sprintf(t::_('The store with URL %s was found by %s() but then was not found by %s().'), $store_url, 'Manifest::has_store_by_url', 'Manifest::get_store_by_url'));
        }
        return $Store;
    }

    /**
     * @return Store[]
     */
    public function get_stores(): array
    {
        $ret = [];
        $DecodedObject = $this->get_decoded_object();
        if ($DecodedObject->stores) {
            foreach ($DecodedObject->stores as $DecodedStore) {
                $ret[] = new Store($DecodedStore->name, $DecodedStore->url, $DecodedStore->added_time);
            }
        }
        return $ret;
    }


    /**
     * Returns all installed components from all stores
     * @return Component[]
     */
    public function get_installed_components(): array
    {
        $ret = [];
        $DecodedObject = $this->get_decoded_object();
        foreach ($DecodedObject->components as $DecodedComponent) {
            $ret[] = new Component(
                $DecodedComponent->name,//package_name
                '',//name
                '',//description
                $DecodedComponent->namespace,
                $DecodedComponent->root_dir,
                $DecodedComponent->src_dir,
                $DecodedComponent->public_src_dir,
                $DecodedComponent->installed_time,
            );
        }
        return $ret;
    }

    /**
     * Returns all available components from all stores
     * @return array
     */
    public function get_available_components(): array
    {
        $ret = [];
        $stores = $this->get_stores();
        foreach ($stores as $Store) {
            $ret = [...$ret, ...$Store->get_available_components()];
        }
        return $ret;
    }

    /**
     * Returns the components that are available for installation.
     * Available components - installed components
     * @return array
     */
    public function get_installable_components(): array
    {

    }
}