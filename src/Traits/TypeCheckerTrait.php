<?php

namespace Rbz\Data\Traits;

trait TypeCheckerTrait
{
    public function isString($key): bool
    {
        return is_string($this->get($key));
    }

    public function isInteger($key): bool
    {
        return is_integer($this->get($key));
    }

    public function isFloat($key): bool
    {
        return is_float($this->get($key));
    }

    public function isArray($key): bool
    {
        return is_array($this->get($key));
    }

    public function isObject($key): bool
    {
        return is_object($this->get($key));
    }

    public function isCallable($key): bool
    {
        return is_callable($this->get($key));
    }

    public function isBool($key): bool
    {
        return is_bool($this->get($key));
    }

    public function isNull($key): bool
    {
        return is_null($this->get($key));
    }

    public function isIterable($key): bool
    {
        return is_iterable($this->get($key));
    }

    public function isScalar($key): bool
    {
        return is_scalar($this->get($key));
    }

    public function isInstanceOf($key, $instance): bool
    {
        return $this->get($key) instanceof $instance;
    }
}
