<?php

namespace Rbz\DataTransfer\Collections\Error;

use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\DataTransfer\Interfaces\Collections\Error\ValueObjects\PathInterface;

class ErrorCollection implements ErrorCollectionInterface
{
    private PathInterface $path;

    /** @var ErrorItemInterface[] */
    private array $items = [];

    public function __construct(PathInterface $path)
    {
        $this->path = $path;
    }

    /**
     * @param string $property
     * @param array|string $messages
     */
    public function add(string $property, $messages): void
    {
        $messages = is_array($messages) ? $messages : (array) $messages;
        $this->addItem(ErrorItem::make($property, $messages, $this->path()));
    }

    public function addItem(ErrorItemInterface $item): void
    {
        if ($this->has($item->getProperty())) {
            $this->get($item->getProperty())->addMessages($item->getMessages());
        } else {
            $this->items[$item->getFullPath()] = $item;
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

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function getPath(): PathInterface
    {
        return $this->path();
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function getFirst(?string $property = null): ?ErrorItemInterface
    {
        if (! is_null($property)) {
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
        $clone->path = $this->path()->with($collection->getPath());
        foreach ($collection->getItems() as $item) {
            $clone->addItem($item->withPath($this->path()));
        }
        return $clone;
    }

    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface
    {
        $this->path = $this->path()->with($collection->getPath());
        foreach ($collection->getItems() as $item) {
            $this->addItem($item->withPath($this->path()));
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
        return array_map(fn(ErrorItem $item) => $item->toArray(), $this->items);
    }

    public function has(string $property): bool
    {
        return key_exists($property, $this->items);
    }

    public function get(string $property): ?ErrorItemInterface
    {
        return $this->items[$property] ?? null;
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
