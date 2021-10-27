<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Rbz\Data\Interfaces\Collections\CollectionInterface as BaseCollectionInterface;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;

interface ErrorCollectionInterface extends BaseCollectionInterface, PathProviderInterface
{
    /**
     * @param ErrorItemInterface $item
     */
    public function addItem($item): void;

    public function getFirstMessage(?string $property = null): ?string;

    public function getFirst(string $property);

    /**
     * @param ErrorCollectionInterface $collection
     * @return static
     */
    public function with($collection);

    /**
     * @param ErrorCollectionInterface $collection
     * @return static
     */
    public function merge($collection);
}
