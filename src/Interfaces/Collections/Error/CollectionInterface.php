<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Rbz\Data\Collections\Error\Item;
use Rbz\Data\Interfaces\Collections\CollectionInterface as BaseCollectionInterface;
use Rbz\Data\Interfaces\Components\PathInterface;

interface CollectionInterface extends BaseCollectionInterface
{
    public function addItem(Item $item): void;
    public function getFirstMessage(?string $property = null): ?string;
    public function getFirst(string $property);
    public function with(CollectionInterface $collection): CollectionInterface;
    public function merge(CollectionInterface $collection): CollectionInterface;
    public function withPath(PathInterface $path): CollectionInterface;
    public function getPath(): PathInterface;

    /**
     * @return ItemInterface[]|array
     */
    public function getItems(): array;
}
