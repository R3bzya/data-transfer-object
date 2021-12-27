<?php

namespace Rbz\Data\Interfaces\Collections;

interface Collectable
{
    /**
     * @return CollectionInterface
     */
    public function toCollection(): CollectionInterface;
}
