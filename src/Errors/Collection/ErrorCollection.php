<?php

namespace Rbz\Forms\Errors\Collection;

use Illuminate\Contracts\Support\Arrayable;

class ErrorCollection implements Arrayable
{
    /** @var ErrorItem[] */
    private array $items = [];

    public function add(ErrorItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getFirst(): ?ErrorItem
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->getItems()[0];
    }

    public function with(ErrorCollection $collection): self
    {
        $clone = clone $this;
        $clone->items = array_merge($this->getItems(), $collection->getItems());
        return $clone;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return ! empty($this->items);
    }

    public function toArray(): array
    {
        return array_map(function (ErrorItem $item) {
            return $item->toArray();
        }, $this->getItems());
    }
}
