<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Exceptions\PropertyException;

trait MagicPropertiesTrait
{
    /**
     * @throws PropertyException
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        throw new PropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * @throws PropertyException
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }
        if (method_exists($this, 'get' . $name)) {
            throw new PropertyException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }
        throw new PropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
    }
}
