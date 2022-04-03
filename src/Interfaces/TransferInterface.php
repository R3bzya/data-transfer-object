<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Errors\ErrorBagProviderInterface;
use Rbz\Data\Interfaces\Support\Collectable;
use Rbz\Data\Interfaces\Support\Arrayable;
use Rbz\Data\Interfaces\Support\Cloneable;
use Rbz\Data\Interfaces\Support\Jsonable;

interface TransferInterface extends PropertiesInterface, ErrorBagProviderInterface,
    Cloneable, Arrayable, Collectable, Jsonable
{
    /**
     * Make the new transfer instance.
     *
     * @param mixed $data
     * @param array $constructArgs
     * @return TransferInterface
     */
    public static function make($data = [], array $constructArgs = []): TransferInterface;

    /**
     * Load the data to the transfer.
     *
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;

    /**
     * Validate the data of the transfer.
     *
     * @param array $properties
     * @param bool $clearErrors
     * @return bool
     */
    public function validate(array $properties = [], bool $clearErrors = true): bool;

    /**
     * Get collection only loaded public properties.
     *
     * @return CollectionInterface
     */
    public function toSafeCollection(): CollectionInterface;
}
