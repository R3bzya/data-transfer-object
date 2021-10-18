<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\Data\Interfaces\Components\PathInterface;

class ErrorCollection extends Collection implements ErrorCollectionInterface
{
    private PathInterface $path;

    /**
     * @param string $key
     * @param null $value
     */
    public function add(string $key, $value = null): void
    {
        $this->addItem(ErrorItem::make($key, $this->getArrayFrom($value), $this->path()));
    }

    public function addItem(ErrorItemInterface $item): void
    {
        if ($this->has($item->getProperty())) {
            $this->get($item->getProperty())->addMessages($item->getMessages());
        } else {
            parent::add($item->getFullPath(), $item);
        }
    }

    public function load(array $data): void
    {
        foreach ($data as $property => $messages) {
            $this->add($property, $messages);
        }
    }

    public function withPath(PathInterface $path): ErrorCollectionInterface
    {
        $this->path = $path;
        return $this;
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
        foreach ($this->items() as $item) {
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
            $clone->addItem($item->withPath($this->path()->with($item->getPath())));
        }
        return $clone;
    }

    public function merge(ErrorCollectionInterface $collection): ErrorCollectionInterface
    {
        foreach ($collection->getItems() as $item) {
            $this->addItem($item->withPath($this->path()->with($item->getPath())));
        }
        return $this;
    }

    public function toArray(): array
    {
        return array_map(fn(ErrorItem $item) => $item->toArray(), $this->items());
    }
}
