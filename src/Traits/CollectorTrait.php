<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Collector;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

trait CollectorTrait
{
    private CollectorInterface $collector;

    public function setCollector(CollectorInterface $collector): void
    {
        $this->collector = $collector;
    }

    public function collector(): CollectorInterface
    {
        if (! isset($this->collector)) {
            $this->collector = new Collector($this->collectable());
        }
        return $this->collector;
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
