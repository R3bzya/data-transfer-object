<?php

namespace Rbz\Data\Traits;

trait TypeCheckerTrait
{
    public function isString(string $key): bool
    {
        return is_string($this->get($key));
    }

    public function isInteger(string $key): bool
    {
        return is_integer($this->get($key));
    }

    public function isFloat(string $key): bool
    {
        return is_float($this->get($key));
    }

    public function isArray(string $key): bool
    {
        return is_array($this->get($key));
    }

    public function isObject(string $key): bool
    {
        return is_object($this->get($key));
    }

    public function isCallable(string $key): bool
    {
        return is_callable($this->get($key));
    }

    public function isBool(string $key): bool
    {
        return is_bool($this->get($key));
    }

    public function isNull(string $key): bool
    {
        return is_null($this->get($key));
    }

    public function isIterable(string $key): bool
    {
        return is_iterable($this->get($key));
    }

    public function isInstanceOf(string $key, $instance): bool
    {
        return $this->get($key) instanceof $instance;
    }
}
