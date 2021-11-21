<?php

namespace Rbz\Data\Interfaces\Collections;

interface TypableInterface
{
    public function isString(string $key): bool;

    public function isInteger(string $key): bool;

    public function isFloat(string $key): bool;

    public function isArray(string $key): bool;

    public function isObject(string $key): bool;

    public function isCallable(string $key): bool;

    public function isBool(string $key): bool;

    public function isNull(string $key): bool;

    public function isIterable(string $key): bool;

    public function isInstanceOf(string $key, $instance): bool;
}
