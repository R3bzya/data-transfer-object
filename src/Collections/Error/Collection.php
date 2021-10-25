<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Collections\Collection as BaseCollection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ItemInterface;
use Rbz\Data\Interfaces\Components\PathInterface;

class Collection extends BaseCollection implements CollectionInterface
{
    private PathInterface $path;

    /**
     * @param string $key
     * @param null $value
     */
    public function add(string $key, $value = null): void
    {
        $this->addItem(Item::make($key, self::getArrayFrom($value), Path::make($key)));
    }

    public function addItem(ItemInterface $item): void
    {
        if ($this->has($item->getProperty())) {
            $this->get($item->getProperty())->addMessages($item->getMessages());
        } else {
            parent::add($item->getPath()->get(), $item);
        }
    }

    public function withPath(PathInterface $path): CollectionInterface
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

    public function hasPath(): bool
    {
        return isset($this->path);
    }

    public function getFirst(?string $property = null): ?ItemInterface
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

    public function with(CollectionInterface $collection): CollectionInterface
    {
        return (clone $this)->merge($collection);
    }

    public function merge(CollectionInterface $collection): CollectionInterface
    {
        foreach ($collection->getItems() as $item) {
            if ($collection->hasPath()) {
                $this->addItem($item->withPath($collection->getPath()->with($item->getPath())));
            } else {
                $this->addItem($item);
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return array_map(fn(Item $item) => $item->toArray(), $this->items());
    }
}
