<?php

namespace Rbz\Forms\Collections\Accessible;

use Rbz\Forms\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\Forms\Interfaces\Collections\CollectionInterface;
use Rbz\Forms\Validator;

class AccessibleCollection implements AccessibleCollectionInterface
{
    /** @var AccessibleItem[] */
    private array $accessible = [];

    public function add(string $rule): void
    {
        if (mb_substr($rule, 0, 1) == Validator::EXCLUSION_SYMBOL) {
            $this->addItem(new AccessibleItem(mb_substr($rule, 1), true));
        } else {
            $this->addItem(new AccessibleItem($rule, false));
        }
    }

    public function addItem(AccessibleItem $item): void
    {
        if ($this->has($item->getAttribute())) {
            if ($item->isExclude() && $this->get($item->getAttribute())->isNotExclude()) {
                $this->get($item->getAttribute())->exclude();
            }
        } else {
            $this->accessible[$item->getAttribute()] = $item;
        }
    }

    public function hasInclude(): bool
    {
        foreach ($this->accessible as $item) {
            if ($item->isNotExclude()) {
                return true;
            }
        }
        return false;
    }

    public function hasExclude(): bool
    {
        foreach ($this->accessible as $item) {
            if ($item->isExclude()) {
                return true;
            }
        }
        return false;
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

    public function toArray(): array
    {
        return array_values(array_map(function (AccessibleItem $item) {
            return $item->toArray();
        }, $this->accessible));
    }

    public function load(array $data): void
    {
        foreach ($data as $rule) {
            $this->add($rule);
        }
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
        return key_exists($attribute, $this->accessible);
    }

    public function get(string $attribute): ?AccessibleItem
    {
        return $this->accessible[$attribute] ?? null;
    }

    private function accessible(): array
    {
        return $this->accessible;
    }

    public function getItems(): array
    {
        return $this->accessible();
    }

    public function isEmpty(): bool
    {
        return empty($this->accessible);
    }

    public function isNotEmpty(): bool
    {
        return ! empty($this->accessible);
    }

    public function count(): int
    {
        return count($this->accessible);
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

    /** @return AccessibleItem[] */
    public function getIncludes(): array
    {
        $includes = [];
        foreach ($this->accessible as $item) {
            if ($item->isNotExclude()) {
                $includes[] = $item;
            }
        }
        return $includes;
    }

    /** @return AccessibleItem[] */
    public function getExcludes(): array
    {
        $includes = [];
        foreach ($this->accessible as $item) {
            if ($item->isExclude()) {
                $includes[] = $item;
            }
        }
        return $includes;
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

    public function isWaitValidation(string $attribute): bool
    {
        if ($this->isEmpty()) {
            return true;
        }

        foreach ($this->getIncludes() as $include) {
            if ($include->getAttribute() == $attribute) {
                return true;
            }
        }

        foreach ($this->getExcludes() as $exclude) {
            if ($exclude->getAttribute() == $attribute) {
                return false;
            }
        }

        return false;
    }
}
