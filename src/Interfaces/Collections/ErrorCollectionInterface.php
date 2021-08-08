<?php

namespace Rbz\Forms\Interfaces\Collections;

use Rbz\Forms\Collections\Error\ErrorItem;

interface ErrorCollectionInterface extends CollectionInterface
{
    public function add(string $attribute, string $message): void;
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $attribute = null): ?string;
    public function getFirst(string $attribute);
}
