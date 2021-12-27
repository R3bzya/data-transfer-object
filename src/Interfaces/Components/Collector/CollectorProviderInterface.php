<?php

namespace Rbz\Data\Interfaces\Components\Collector;

interface CollectorProviderInterface
{
    /**
     * Set the collector to the instance.
     *
     * @param CollectorInterface $collector
     * @return static
     */
    public function setCollector(CollectorInterface $collector);

    /**
     * Get the collector.
     *
     * @return CollectorInterface
     */
    public function getCollector(): CollectorInterface;
}
