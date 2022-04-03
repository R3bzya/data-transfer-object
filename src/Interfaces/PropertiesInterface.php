<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Support\CollectionInterface;

interface PropertiesInterface
{
    /**
     * Set the data to transfer.
     *
     * @param array $data
     */
    public function setProperties(array $data): void;

    /**
     * Set the value in the transfer by property.
     *
     * @param string $property
     * @param mixed $value
     */
    public function setProperty(string $property, $value): void;

    /**
     * Get collection only public properties.
     *
     * @return CollectionInterface
     */
    public function getProperties(): CollectionInterface;

    /**
     * Get the value from the transfer by property.
     *
     * @param string $property
     * @return mixed
     */
    public function getProperty(string $property);

    /**
     * Determine if the public property exists in the transfer.
     *
     * @param string $property
     * @return bool
     */
    public function hasProperty(string $property): bool;

    /**
     * Determine if the property is set in the transfer.
     *
     * @param string $property
     * @return bool
     */
    public function isSetProperty(string $property): bool;
}
