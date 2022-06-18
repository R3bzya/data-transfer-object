<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Collector;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

trait CollectorTrait
{
    private CollectorInterface $collector;

    public function setCollector(CollectorInterface $collector)
    {
        $this->collector = $collector;
        return $this;
    }

    public function collector(): CollectorInterface
    {
        if (! isset($this->collector)) {
            $this->collector = Collector::make($this->collectables());
        }
        return $this->collector;
    }

    public function getCollector(): CollectorInterface
    {
        return $this->collector;
    }

    public function collectables(): array
    {
        return [];
    }
}
