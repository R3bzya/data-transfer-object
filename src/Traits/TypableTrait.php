<?php

namespace Rbz\Data\Traits;

trait TypableTrait
{
    public function isString(string $key): bool
    {
        return $this->has($key) && is_string($this->get($key));
    }

    public function isInteger(string $key): bool
    {
        return $this->has($key) && is_integer($this->get($key));
    }

    public function isFloat(string $key): bool
    {
        return $this->has($key) && is_float($this->get($key));
    }

    public function isArray(string $key): bool
    {
        return $this->has($key) && is_array($this->get($key));
    }

    public function isObject(string $key): bool
    {
        return $this->has($key) && is_object($this->get($key));
    }

    public function isCallable(string $key): bool
    {
        return $this->has($key) && is_callable($this->get($key));
    }

    public function isBool(string $key): bool
    {
        return $this->has($key) && is_bool($this->get($key));
    }

    public function isNull(string $key): bool
    {
        return $this->has($key) && is_null($this->get($key));
    }

    public function isIterable(string $key): bool
    {
        return $this->has($key) && is_iterable($this->get($key));
    }

    public function isInstanceOf(string $key, $instance): bool
    {
        return $this->has($key) && $this->get($key) instanceof $instance;
    }
}
