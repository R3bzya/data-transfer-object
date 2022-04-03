<?php

namespace Rbz\Data\Interfaces\Support;

interface Collectable
{
    /**
     * @return CollectionInterface
     */
    public function toCollection(): CollectionInterface;
}
