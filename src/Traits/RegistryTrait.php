<?php

namespace MichaelRamirezApi\Traits;
use Symfony\Component\Routing\Exception\InvalidParameterException;

trait RegistryTrait{
    protected $storage = [];

    /**
     * Add value to registry
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function add($key, $value){
        $this->storage[$key] = $value;
    }
    
    /**
     * Get value from key
     * @param mixed $key
     * @return array
     */
    public function get($key){
        /*if (!$this->existsKey($key))
            throw new InvalidParameterException('The key no exists');*/

        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }

    /**
     * Verify if key exists
     * @param mixed $key
     * @return bool
     */
    public function existsKey($key){
        return array_key_exists($key, $this->storage);
    }

    /**
     * Verify if value exists
     * @param mixed $value
     * @return bool
     */
    public function exists($value){
        return in_array($value, $this->storage);
    }

    /**
     * Delete value from registry
     * @param mixed $key
     * @return void
     */
    public function delete($key){
        unset($this->storage[$key]);
    }
}