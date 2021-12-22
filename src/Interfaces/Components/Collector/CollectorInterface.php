<?php

namespace Rbz\Data\Interfaces\Components\Collector;

interface CollectorInterface
{
    public static function make(array $collectable): CollectorInterface;
    public function getCollectable(): array;
    public function collect(string $property, array $data): array;
    public function has(string $property): bool;
    public function get(string $property): string;
}
