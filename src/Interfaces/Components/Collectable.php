<?php

namespace Rbz\Data\Interfaces\Components;

use Rbz\Data\Interfaces\Collections\CollectionInterface;

interface Collectable
{
    public function toCollection(): CollectionInterface;
}
