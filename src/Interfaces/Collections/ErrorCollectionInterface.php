<?php

namespace Rbz\DataTransfer\Interfaces\Collections;

use Rbz\DataTransfer\Collections\Error\ErrorItem;

interface ErrorCollectionInterface extends CollectionInterface
{
    public function add(string $attribute, string $message): void;
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $attribute = null): ?string;
    public function getFirst(string $attribute);
    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface;
}
