<?php

namespace Rbz\Data\Interfaces\Components\Collector;

interface CollectorInterface
{
    /**
     * Make the new collector instance.
     *
     * @param array $collectables
     * @return static
     */
    public static function make(array $collectables);

    /**
     * Collect the data by property.
     *
     * @param string $property
     * @param array $data
     * @return array
     */
    public function toCollect(string $property, array $data): array;

    /**
     * Determine if the class exists in the collector by property.
     *
     * @param string $property
     * @return bool
     */
    public function has(string $property): bool;

    /**
     * Get the name of the class by property.
     *
     * @param string $property
     * @return string
     */
    public function get(string $property): string;
}
