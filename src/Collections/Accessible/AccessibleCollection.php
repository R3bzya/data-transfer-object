<?php

namespace Rbz\DataTransfer\Collections\Accessible;

use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Components\Filter;

/** @deprecated  */
class AccessibleCollection implements AccessibleCollectionInterface
{
    /** @var AccessibleItem[] */
    private array $items = [];

    public function add(string $rule): void
    {
        if (str_starts_with($rule, Filter::SYMBOL_EXCLUDE)) {
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
            $this->items[$item->getAttribute()] = $item;
        }
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

    public function filterKeys(array $attributes): array
    {
        $filtered = $this->filter(array_keys($attributes));
        return array_filter_keys($attributes, function (string $key) use ($filtered) {
            return in_array($key, $filtered);
        });
    }

    public function filter(array $attributes): array
    {
        if ($this->isEmpty()) {
            return $attributes;
        }
        if ($this->hasInclude()) {
            return $this->filterNotIncluded($attributes);
        }
        return $this->filterExcluded($attributes);
    }

    public function filterNotIncluded(array $attributes): array
    {
        return array_filter($attributes, fn(string $attribute) => $this->isInclude($attribute));
    }

    public function filterExcluded(array $attributes): array
    {
        return array_filter($attributes, fn(string $attribute) => ! $this->isExclude($attribute));
    }

    public function toArray(): array
    {
        return array_values(array_map(fn(AccessibleItem $item) => $item->toArray(), $this->items));
    }

    public function load(array $data): void
    {
        foreach ($data as $rule) {
            $this->add($rule);
        }
    }

    public function with(AccessibleCollectionInterface $collection): AccessibleCollectionInterface
    {
        $clone = clone $this;
        foreach ($collection->getItems() as $item) {
            $clone->addItem($item);
        }
        return $clone;
    }

    public function merge(AccessibleCollectionInterface $collection): AccessibleCollectionInterface
    {
        foreach ($collection->getItems() as $item) {
            $this->addItem($item);
        }
        return $this;
    }

    public function has(string $attribute): bool
    {
        return key_exists($attribute, $this->items);
    }

    public function get(string $attribute): ?AccessibleItem
    {
        return $this->items[$attribute] ?? null;
    }

    private function items(): array
    {
        return $this->items;
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @return AccessibleItem[] */
    public function getIncludes(): array
    {
        return array_filter($this->items(), fn(AccessibleItem $item) => $item->isNotExclude());
    }

    /** @return AccessibleItem[] */
    public function getExcludes(): array
    {
        return array_filter($this->items(), fn(AccessibleItem $item) => $item->isExclude());
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function isInclude(string $attribute): bool
    {
        return in_array($attribute, array_keys($this->getIncludes()));
    }

    public function isExclude(string $attribute): bool
    {
        return in_array($attribute, array_keys($this->getExcludes()));
    }
}
