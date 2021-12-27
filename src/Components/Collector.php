<?php

namespace Rbz\Data\Components;

use Rbz\Data\Exceptions\CollectorException;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;
use ReflectionClass;

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
        return array_map(fn(array $datum) => $this->collect($this->get($property), $datum), $data);
    }

    private function collect(string $objectClass, array $data)
    {
        $reflection = new ReflectionClass($objectClass);
        if (! $reflection->isInstantiable()) {
            throw new CollectorException("Cannot instantiate class {$reflection->getName()}");
        }
        if (! $reflection->hasMethod('make')) {
            throw new CollectorException("Method make does not exist {$reflection->getName()}::make()");
        }
        return $this->makeInstance($reflection, $data);
    }

    private function makeInstance(ReflectionClass $reflection, array $data)
    {
        $methodMake = $reflection->getMethod('make');
        if (! $methodMake->isPublic()) {
            throw new CollectorException("Method make is non public {$reflection->getName()}::make()");
        }
        if (! $methodMake->isStatic()) {
            throw new CollectorException("Method make is non static {$reflection->getName()}::make()");
        }
        return $methodMake->invoke(null, $data);
    }

    public function has(string $property): bool
    {
        return key_exists($property, $this->collectables);
    }

    public function get(string $property): string
    {
        if (! $this->has($property)) {
            throw new CollectorException("Collector does not have $property");
        }
        return $this->collectables[$property];
    }
}
