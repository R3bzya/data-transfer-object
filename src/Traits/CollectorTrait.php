<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Collector;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

trait CollectorTrait
{
    private CollectorInterface $_collector;

    public function setCollector(CollectorInterface $collector)
    {
        $this->_collector = $collector;
        return $this;
    }

    public function collector(): CollectorInterface
    {
        if (! isset($this->_collector)) {
            $this->_collector = Collector::make($this->collectables());
        }
        return $this->_collector;
    }

    public function getCollector(): CollectorInterface
    {
        return $this->_collector;
    }

    public function collectables(): array
    {
        return [];
    }
}
