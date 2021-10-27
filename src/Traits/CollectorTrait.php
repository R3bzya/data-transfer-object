<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Collector;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

trait CollectorTrait
{
    protected array $collectable = [];

    private CollectorInterface $collector;

    public function setCollector(CollectorInterface $collector): void
    {
        $this->collector = $collector;
    }

    public function collector(): CollectorInterface
    {
        if (! isset($this->collector)) {
            $this->collector = new Collector($this->collectable);
        }
        return $this->collector;
    }

    public function getCollector(): CollectorInterface
    {
        return $this->collector();
    }
}
