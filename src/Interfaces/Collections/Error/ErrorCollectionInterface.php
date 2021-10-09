<?php

namespace Rbz\DataTransfer\Interfaces\Collections\Error;

use Rbz\DataTransfer\Collections\Error\ErrorItem;
use Rbz\DataTransfer\Interfaces\Collections\CollectionInterface;

interface ErrorCollectionInterface extends CollectionInterface
{
    public function add(string $attribute, $messages): void;
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $attribute = null): ?string;
    public function getFirst(string $attribute);
    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface;
}
