<?php

namespace Rbz\Data\Validation\Helpers;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;

class RuleHelper
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
            ->filter(fn(string $property) => ! in_array('!'.$property, $properties))
            ->filter(fn(string $property) => ! self::hasInclusions($properties) || in_array($property, $properties))
            ->toArray();
    }

    private static function hasInclusions(array $properties): bool
    {
        foreach ($properties as $property) {
            if (! str_starts_with($property, '!')) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $properties
     * @return array
     */
    public function run(array $properties): array
    {
        if (! $this->hasRules()) {
            return $this->makeDefaultRules($properties)->toArray();
        }
        $rules = Collection::make($this->rules)->only($properties);
        $differentProperties = Collection::make($properties)->diff($rules->keys());
        return $differentProperties->isEmpty()
            ? $rules->toArray()
            : $rules->merge($this->makeDefaultRules($differentProperties->toArray()))->toArray();
    }

    private function hasRules(): bool
    {
        return count($this->rules) > 0;
    }

    private function makeDefaultRules(array $properties): CollectionInterface
    {
        return Collection::make($properties)->flip()->map(fn($value, string $property) => ['present']); // TODO тут не должно быть `present`
    }
}
