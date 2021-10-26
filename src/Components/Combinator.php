<?php

namespace Rbz\Data\Components;

use Rbz\Data\Interfaces\Components\CombinatorInterface;

class Combinator implements CombinatorInterface
{
    private array $combinations;

    public function __construct(array $combinations)
    {
        $this->combinations = $combinations;
    }

    public function make(array $combinations): CombinatorInterface
    {
        return new self($combinations);
    }

    public function combinations(): array
    {
        return $this->combinations;
    }

    public function getCombinations(): array
    {
        return $this->combinations();
    }

    public function combine(string $property, array $data): array
    {
        return array_map(fn(array $datum) => call_user_func([$this->get($property), 'make'], $datum), $data);
    }

    public function has(string $property): bool
    {
        return key_exists($property, $this->combinations);
    }

    public function get(string $property): string
    {
        return $this->combinations()[$property];
    }
}
