<?php

namespace Rbz\Data\Interfaces\Components;

interface CombinatorInterface
{
    public function getCombinations(): array;
    public function combine(string $property, array $data): array;
    public function hasProperty(string $property): bool;
    public function canCombine(string $property): bool;
}
