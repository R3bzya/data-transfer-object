<?php

namespace Rbz\Data\Interfaces\Events;

use Rbz\Data\Interfaces\Components\StorageInterface as BaseStorage;

interface StorageInterface extends BaseStorage
{
    public function getEvents(): array;
}