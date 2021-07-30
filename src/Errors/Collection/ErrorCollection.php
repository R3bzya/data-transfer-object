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
            $this->items[$item->getAttribute()] = $item;
        }
    }

    public function items(): array
    {
        return $this->items;
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function getFirst(?string $attribute = null): ?ErrorItem
    {
        if ($attribute) {
            return $this->get($attribute);
        }

        foreach ($this->items as $item) {
            return $item;
        }

        return null;
    }

    public function getFirstMessage(?string $attribute = null): ?string
    {
        if ($error = $this->getFirst($attribute)) {
            return $error->getMessage();
        }
        return null;
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
        return array_values(array_map(function (ErrorItem $item) {
            return $item->toArray();
        }, $this->items));
    }

    public function has(string $attribute): bool
    {
        return key_exists($attribute, $this->items);
    }

    public function get(string $attribute): ?ErrorItem
    {
        return $this->items[$attribute] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function countErrorsEqualTo(int $count): bool
    {
        return $this->count() == $count;
    }
}
