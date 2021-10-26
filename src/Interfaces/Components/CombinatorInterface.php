<?php

namespace Rbz\Data\Interfaces\Components;

interface CombinatorInterface
{
    public function make(array $combinations): CombinatorInterface;
    public function getCombinations(): array;
    public function combine(string $property, array $data): array;
    public function has(string $property): bool;
    public function get(string $property): string;
}
