<?php

namespace Rbz\Data\Interfaces\Events;

use Rbz\Data\Interfaces\StorageInterface as BaseStorage;

interface StorageInterface extends BaseStorage
{
    public function getEvents(): array;
}