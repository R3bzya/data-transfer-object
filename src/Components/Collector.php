<?php

namespace Rbz\Data\Components;

use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

class Collector implements CollectorInterface
{
    private array $collectable;

    public function __construct(array $collectable)
    {
        $this->collectable = $collectable;
    }

    public function make(array $collectable): CollectorInterface
    {
        return new self($collectable);
    }

    public function collectable(): array
    {
        return $this->collectable;
    }

    public function getCollectable(): array
    {
        return $this->collectable();
    }

    public function collect(string $property, array $data): array
    {
        return array_map(fn(array $datum) => call_user_func([$this->get($property), 'make'], $datum), $data);
    }

    public function has(string $property): bool
    {
        return key_exists($property, $this->collectable());
    }

    public function get(string $property): string
    {
        return $this->collectable()[$property];
    }
}
