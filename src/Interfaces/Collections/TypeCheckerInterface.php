<?php

namespace Rbz\Data\Interfaces\Collections;

interface TypeCheckerInterface
{
    /**
     * Determine if the value is string in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isString($key): bool;

    /**
     * Determine if the value is integer in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isInteger($key): bool;

    /**
     * Determine if the value is float in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isFloat($key): bool;

    /**
     * Determine if the value is array in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isArray($key): bool;

    /**
     * Determine if the value is object in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isObject($key): bool;

    /**
     * Determine if the value is callable in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isCallable($key): bool;

    /**
     * Determine if the value is bool in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isBool($key): bool;

    /**
     * Determine if the value is null in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isNull($key): bool;

    /**
     * Determine if the value is iterable in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isIterable($key): bool;

    /**
     * Determine if the value is scalar in the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function isScalar($key): bool;

    /**
     * Determine if the value is instance of the given value in the collection.
     *
     * @param mixed $key
     * @param mixed $instance
     * @return bool
     */
    public function isInstanceOf($key, $instance): bool;
}
