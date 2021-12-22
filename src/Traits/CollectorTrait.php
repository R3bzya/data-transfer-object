<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Collector;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

trait CollectorTrait
{
    public function collector(): CollectorInterface
    {
        return Collector::make($this->collectable());
    }

    public function getCollector(): CollectorInterface
    {
        return $this->collector();
    }

    public function collectable(): array
    {
        return [];
    }
}
