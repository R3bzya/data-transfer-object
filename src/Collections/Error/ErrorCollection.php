<?php

namespace Rbz\DataTransfer\Collections\Error;

use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorItemInterface;

class ErrorCollection implements ErrorCollectionInterface
{
    /** @var ErrorItemInterface[] */
    private array $items = [];

    /**
     * @param array|string $attribute
     * @param string $messages
     */
    public function add(string $attribute, $messages): void
    {
        $this->addItem(new ErrorItem($attribute, is_array($messages) ? $messages : (array) $messages));
    }

    public function addItem(ErrorItemInterface $item): void
    {
        if ($this->has($item->getProperty())) {
            $this->get($item->getProperty())->addMessages($item->getMessages());
        } else {
            $this->items[$item->getProperty()] = $item;
        }
    }

    public function load(array $data): void
    {
        foreach ($data as $attribute => $messages) {
            $this->addItem(new ErrorItem($attribute, $messages));
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

    public function getFirst(?string $attribute = null): ?ErrorItemInterface
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

    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface
    {
        $clone = clone $this;
        foreach ($collection->getItems() as $item) {
            $clone->addItem($item);
        }
        return $clone;
    }

    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface
    {
        foreach ($collection->getItems() as $item) {
            $this->addItem($item);
        }
        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    public function toArray(): array
    {
        return array_values(array_map(fn(ErrorItem $item) => $item->toArray(), $this->items));
    }

    public function has(string $attribute): bool
    {
        return key_exists($attribute, $this->items);
    }

    public function get(string $attribute): ErrorItemInterface
    {
        if (! $this->has($attribute)) {
            throw new \DomainException("Property $attribute not found");
        }
        return $this->items[$attribute];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }
}
