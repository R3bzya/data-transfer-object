<?php

namespace Rbz\Data\Support\Transfer;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Rules
{
    private array $rules;

    /**
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param CollectionInterface $transferProperties
     * @param array $properties
     * @return array
     */
    public static function toValidation(CollectionInterface $transferProperties, array $properties): array
    {
        if (! count($properties)) {
            return $transferProperties->toArray();
        }
        return $transferProperties
            ->filter(fn(string $property) => Arr::notIn($properties, '!'.$property))
            ->filter(fn(string $property) => ! self::hasInclusions($properties) || Arr::in($properties, $property))
            ->toArray();
    }

    private static function hasInclusions(array $properties): bool
    {
        foreach ($properties as $property) {
            if (! Str::startWith($property, '!')) {
                return true;
            }
        }
        return false;
    }

    /**
     * @modified 03.04.2022
     * @param array $properties
     * @return array
     */
    public function run(array $properties): array
    {
        return Collection::make($this->rules)->only($properties)->toArray();
    }
}
