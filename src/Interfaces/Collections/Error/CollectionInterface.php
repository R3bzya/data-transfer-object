<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Rbz\Data\Collections\Error\Item;
use Rbz\Data\Interfaces\Collections\CollectionInterface as BaseCollectionInterface;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;

interface CollectionInterface extends BaseCollectionInterface, PathProviderInterface
{
    public function addItem(Item $item): void;
    public function getFirstMessage(?string $property = null): ?string;
    public function getFirst(string $property);
    public function with(CollectionInterface $collection): CollectionInterface;
    public function merge(CollectionInterface $collection): CollectionInterface;

    /**
     * @return ItemInterface[]
     */
    public function getItems(): array;
}
