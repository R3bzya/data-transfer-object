<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Collections\CollectionInterface;

interface PropertiesInterface
{
    /**
     * @param array $data
     */
    public function setProperties(array $data): void;

    /**
     * @param string $property
     * @param $value
     */
    public function setProperty(string $property, $value): void;

    /**
     * @return CollectionInterface
     */
    public function getProperties(): CollectionInterface;

    /**
     * @param string $property
     * @return mixed
     */
    public function getProperty(string $property);

    /**
     * @param string $property
     * @return bool
     */
    public function hasProperty(string $property): bool;

    /**
     * @param string $property
     * @return bool
     */
    public function isSetProperty(string $property): bool;

    /**
     * @param string $property
     * @return bool
     */
    public function isNullProperty(string $property): bool;

    /**
     * @param string $property
     * @return bool
     */
    public function isPublicProperty(string $property): bool;
}
