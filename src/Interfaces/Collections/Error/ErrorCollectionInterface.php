<?php

namespace Rbz\DataTransfer\Interfaces\Collections\Error;

use Rbz\DataTransfer\Collections\Error\ErrorItem;
use Rbz\DataTransfer\Interfaces\Collections\CollectionInterface;

interface ErrorCollectionInterface extends CollectionInterface
{
    public function add(string $property, $messages): void;
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $property = null): ?string;
    public function getFirst(string $property);
    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface;
}
