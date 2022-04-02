<?php

namespace Rbz\Data\Components;

use Rbz\Data\Exceptions\CollectorException;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;
use Rbz\Data\Support\Arr;

class Collector implements CollectorInterface
{
    private array $collectables;

    public function __construct(array $collectables)
    {
        $this->collectables = $collectables;
    }

    public static function make(array $collectables): CollectorInterface
    {
        return new static($collectables);
    }

    public function toCollect(string $property, array $data): array
    {
        return Arr::map(fn(array $datum) => $this->collect($this->get($property), $datum), $data);
    }

    private function collect(string $objectClass, array $data)
    {
        if  (! method_exists($objectClass, 'make')) {
            throw new CollectorException("Method make does not exist {$objectClass}::make()");
        }
        return call_user_func([$objectClass, 'make'], $data);
    }

    public function has(string $property): bool
    {
        return Arr::has($this->collectables, $property);
    }

    public function get(string $property): string
    {
        if (! $this->has($property)) {
            throw new CollectorException("Collector does not have $property");
        }
        return $this->collectables[$property];
    }
}
