<?php

namespace Rbz\DataTransfer\Interfaces\Components;

interface CombinatorInterface
{
    public function __construct(array $combinations);
    public function getCombinations(): array;
    public function isCombined(string $property): bool;
    public function combine(string $property, array $data): array;
    public function hasProperty(string $property): bool;
}
