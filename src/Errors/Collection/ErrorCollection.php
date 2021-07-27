<?php

namespace Rbz\Forms\Errors\Collection;

use Illuminate\Contracts\Support\Arrayable;

class ErrorCollection implements Arrayable
{
    /** @var ErrorItem[] */
    private array $items = [];

    public function add(string $attribute, string $message): void
    {
        $this->addItem(new ErrorItem($attribute, (array) $message));
    }

    public function addItem(ErrorItem $item): void
    {
        if ($this->has($item->getAttribute())) {
            $this->get($item->getAttribute())->addMessages($item->getMessages());
        } else {
            $this->items[] = $item;
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getFirst(): ?ErrorItem
    {
        return array_first($this->items);
    }

    public function with(ErrorCollection $collection): self
    {
        $clone = clone $this;
        foreach ($collection->getItems() as $item) {
            $clone->addItem($item);
        }
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
        }, $this->items);
    }

    public function has(string $attribute): bool
    {
        foreach ($this->items as $item) {
            if ($item->equalTo($attribute)) {
                return true;
            }
        }
        return false;
    }

    public function get(string $attribute): ?ErrorItem
    {
        foreach ($this->items as $item) {
            if ($item->equalTo($attribute)) {
                return $item;
            }
        }
        return null;
    }
}
