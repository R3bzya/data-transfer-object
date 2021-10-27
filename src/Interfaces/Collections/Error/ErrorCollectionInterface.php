<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Rbz\Data\Collections\Error\ErrorItem;
use Rbz\Data\Interfaces\Collections\CollectionInterface as BaseCollectionInterface;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;

interface ErrorCollectionInterface extends BaseCollectionInterface, PathProviderInterface
{
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $property = null): ?string;
    public function getFirst(string $property);
    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface;

    /**
     * TODO что-то придумать
     * @return ErrorItemInterface[]
     *
     */
    public function getItems(): array;
}
