<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Rbz\Data\Collections\Error\ErrorItem;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Components\PathInterface;

interface ErrorCollectionInterface extends CollectionInterface
{
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $property = null): ?string;
    public function getFirst(string $property);
    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function withPath(PathInterface $path): ErrorCollectionInterface;
    public function getPath(): PathInterface;
}
