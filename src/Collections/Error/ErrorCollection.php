<?php

namespace Rbz\DataTransfer\Collections\Error;

use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorItemInterface;

class ErrorCollection implements ErrorCollectionInterface
{
    /** @var ErrorItemInterface[] */
    private array $items = [];

    /**
     * @param array|string $property
     * @param string $messages
     */
    public function add(string $property, $messages): void
    {
        $this->addItem(new ErrorItem($property, is_array($messages) ? $messages : (array) $messages));
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
        foreach ($data as $property => $messages) {
            $this->add($property, $messages);
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

    public function getFirst(?string $property = null): ?ErrorItemInterface
    {
        if ($property) {
            return $this->get($property);
        }

        foreach ($this->items as $item) {
            return $item;
        }

        return null;
    }

    public function getFirstMessage(?string $property = null): ?string
    {
        if ($error = $this->getFirst($property)) {
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

    public function has(string $property): bool
    {
        return key_exists($property, $this->items);
    }

    public function get(string $property): ErrorItemInterface
    {
        if (! $this->has($property)) {
            throw new \DomainException("Property $property not found");
        }
        return $this->items[$property];
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
