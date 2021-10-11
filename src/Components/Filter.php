<?php

namespace Rbz\DataTransfer\Components;

use Rbz\DataTransfer\Interfaces\Components\FilterInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class Filter implements FilterInterface
{
    const SYMBOL_EXCLUDE = '!';

    private array $properties;

    /** @var string[] */
    private array $exclude;

    /** @var string[] */
    private array $include;

    public function __construct(array $properties, array $rules)
    {
        $this->properties = $properties;
        $this->exclude = $this->getExcludeFrom($rules);
        $this->include = $this->getIncludeFrom($rules);
    }

    public static function make(array $properties, array $exclude): FilterInterface
    {
        return new self($properties, $exclude);
    }

    public function filterTransfer(TransferInterface $transfer): array
    {
        $array = [];
        foreach ($this->filtered() as $property) {
            $array[$property] = $transfer->getProperty($property);
        }
        return $array;
    }

    /** @deprecated  */
    public function filterArray(array $array): array
    {
        return array_filter($array, function ($value) {
            return in_array($value, $this->filtered());
        });
    }

    public function filterArrayKeys(array $data): array
    {
        return array_filter_keys($data, function (string $property) {
            return in_array($property, $this->filtered());
        });
    }

    public function filtered(): array
    {
        if ($this->hasInclude()) {
            return $this->filterNotInclude($this->properties());
        }
        return $this->filterExclude($this->properties());
    }

    public function getExcludeFrom(array $data): array
    {
        return array_map(fn(string $rule) => mb_substr($rule, 1), $this->getRawExcludeFrom($data));
    }

    public function getRawExcludeFrom(array $data): array
    {
        return array_filter($data, fn(string $rule) => $this->isExclude($rule));
    }

    public function getIncludeFrom(array $data): array
    {
        return array_filter($data, fn(string $rule) => $this->isInclude($rule));
    }

    public function isInclude(string $rule): bool
    {
        return ! str_starts_with($rule, self::SYMBOL_EXCLUDE);
    }

    public function isExclude(string $rule): bool
    {
        return str_starts_with($rule, self::SYMBOL_EXCLUDE);
    }

    public function hasInclude(): bool
    {
        return count($this->include());
    }

    public function hasExclude(): bool
    {
        return count($this->exclude());
    }

    public function filterNotInclude(array $properties): array
    {
        return array_filter($properties, fn(string $property) => $this->inInclude($property));
    }

    public function filterExclude(array $properties): array
    {
        return array_filter($properties, fn(string $property) => ! $this->inExclude($property));
    }

    public function include(): array
    {
        return $this->include;
    }

    public function exclude(): array
    {
        return $this->exclude;
    }

    public function properties(): array
    {
        return $this->properties;
    }

    public function inInclude(string $property): bool
    {
        return in_array($property, $this->include());
    }

    public function inExclude(string $property): bool
    {
        return in_array($property, $this->exclude());
    }

    /**
     * ToDo пока не понятно что с этим делать
     * @return array
     */
    public function all(): array
    {
        return $this->properties();
        //return array_unique(array_merge($this->properties(), $this->include()));
    }

    public function getRules(): array
    {
        return array_merge($this->include(), $this->exclude());
    }
}