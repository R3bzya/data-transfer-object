<?php

namespace Rbz\Forms\Collections\Validation;

use Rbz\Forms\Interfaces\CollectionInterface;

class ValidationAttributeCollection implements CollectionInterface
{
    /** @var ExceptionItem[] */
    private array $items = [];

    public function add(string $rule): void
    {
        if (mb_substr($rule, 0, 1) == '!') {
            $this->addItem(new ExceptionItem(mb_substr($rule, 1), true));
        } else {
            $this->addItem(new ExceptionItem($rule, false));
        }
    }

    public function addItem(ExceptionItem $item): void
    {
        if ($this->has($item->getAttribute())) {
            if ($item->isExclude() && $this->get($item->getAttribute())->isNotExclude()) {
                $this->get($item->getAttribute())->exclude();
            }
        } else {
            $this->items[$item->getAttribute()] = $item;
        }
    }

    public function load(array $data): void
    {
        foreach ($data as $rule) {
            $this->add($rule);
        }
    }

    private function items(): array
    {
        return $this->items;
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function toArray(): array
    {
        return array_values(array_map(function (ExceptionItem $item) {
            return $item->toArray();
        }, $this->items));
    }

    public function with(CollectionInterface $collection): self
    {
        $clone = clone $this;
        foreach ($collection->getItems() as $item) {
            $clone->addItem($item);
        }
        return $clone;
    }

    public function has(string $attribute): bool
    {
        return key_exists($attribute, $this->items);
    }

    public function get(string $attribute): ?ExceptionItem
    {
        return $this->items[$attribute] ?? null;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return ! empty($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function filter(array $attributes, bool $keys = false): array
    {
        if ($this->isEmpty()) {
            return $attributes;
        }

        if ($this->hasInclude()) {
            $filtered = $this->filterInclude($this->getAttributes($attributes, $keys));
        } else {
            $filtered = $this->filterExcludes($this->getAttributes($attributes, $keys));
        }

        return $keys ? $this->getOnly($attributes, $filtered) : $filtered;
    }

    public function hasInclude(): bool
    {
        foreach ($this->items as $item) {
            if ($item->isNotExclude()) {
                return true;
            }
        }
        return false;
    }

    public function hasExclude(): bool
    {
        foreach ($this->items as $item) {
            if ($item->isExclude()) {
                return true;
            }
        }
        return false;
    }

    /** @return ExceptionItem[] */
    public function getIncludes(): array
    {
        $includes = [];
        foreach ($this->items as $item) {
            if ($item->isNotExclude()) {
                $includes[] = $item;
            }
        }
        return $includes;
    }

    /** @return ExceptionItem[] */
    public function getExcludes(): array
    {
        $includes = [];
        foreach ($this->items as $item) {
            if ($item->isExclude()) {
                $includes[] = $item;
            }
        }
        return $includes;
    }

    private function filterInclude(array $attributes): array
    {
        $filtered = [];
        foreach ($this->getIncludes() as $include) {
            if (in_array($include->getAttribute(), $attributes)) {
                $filtered[] = $include->getAttribute();
            }
        }
        return $filtered;
    }

    private function filterExcludes(array $attributes): array
    {
        $filtered = [];
        foreach ($attributes as $attribute) {
            if ($this->isNotExclude($attribute)) {
                $filtered[] = $attribute;
            }
        }
        return $filtered;
    }

    public function isNotExclude(string $attribute): bool
    {
        return ! $this->has($attribute) || $this->get($attribute)->isNotExclude();
    }

    private function getOnly(array $attributes, array $only): array
    {
        return array_filter_keys($attributes, function ($attribute) use ($only) {
            return in_array($attribute, $only);
        });
    }

    private function getAttributes(array $attributes, bool $keys): array
    {
        return $keys ? array_keys($attributes) : $attributes;
    }
}
