<?php

namespace Rbz\DataTransfer\Traits;

use DomainException;

trait MagicPropertiesTrait
{
    /**
     * @throws DomainException
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        throw new DomainException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * @throws DomainException
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }
        if (method_exists($this, 'get' . $name)) {
            throw new DomainException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }
        throw new DomainException('Setting unknown property: ' . get_class($this) . '::' . $name);
    }
}
