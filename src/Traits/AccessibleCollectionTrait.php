<?php

namespace Rbz\DataTransfer\Traits;

use Rbz\DataTransfer\Collections\Accessible\AccessibleCollection;
use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;

trait AccessibleCollectionTrait
{
    private AccessibleCollectionInterface $accessibleCollection;

    public function setAccessible(AccessibleCollectionInterface $collection): void
    {
        $this->accessibleCollection = $collection;
    }

    public function accessible(): AccessibleCollectionInterface
    {
        if (! isset($this->accessibleCollection)) {
            $this->accessibleCollection = new AccessibleCollection();
        }
        return $this->accessibleCollection;
    }

    public function getAccessible(): AccessibleCollectionInterface
    {
        return $this->accessible();
    }
}
