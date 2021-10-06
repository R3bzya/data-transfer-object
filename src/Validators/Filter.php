<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\Validators\FilterInterface;
use Rbz\DataTransfer\Transfer;
use function array_filter_keys;

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
        $this->exclude = $this->prepareExclude($rules);
        $this->include = $this->prepareInclude($rules);
    }

    public function filterTransfer(Transfer $transfer): array
    {
        $array = [];
        foreach ($this->filter() as $property) {
            $array[$property] = $transfer->getProperty($property);
        }
        return $array;
    }

    /** @deprecated  */
    public function filterArray(array $array): array
    {
        return array_filter($array, function ($value) {
            return in_array($value, $this->filter());
        });
    }

    public function filterArrayKeys(array $data): array
    {
        return array_filter_keys($data, function (string $property) {
            return in_array($property, $this->filter());
        });
    }

    public function filter(): array
    {
        if ($this->hasInclude()) {
            return $this->filterInclude($this->properties());
        }
        return $this->filterExclude($this->properties());
    }

    public function prepareExclude(array $rules): array
    {
        return array_map(fn(string $rule) => mb_substr($rule, 1),
            array_filter($rules, fn(string $rule) => $this->isExclude($rule))
        );
    }

    public function prepareInclude(array $rules): array
    {
        return array_filter($rules, fn(string $rule) => $this->isInclude($rule));
    }

    public function isInclude(string $rule): bool
    {
        return mb_substr($rule, 0, 1) != self::SYMBOL_EXCLUDE;
    }

    public function isExclude(string $rule): bool
    {
        return mb_substr($rule, 0, 1) == self::SYMBOL_EXCLUDE;
    }

    public function hasInclude(): bool
    {
        return count($this->include());
    }

    public function hasExclude(): bool
    {
        return count($this->exclude());
    }

    public function filterInclude(array $properties): array
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
