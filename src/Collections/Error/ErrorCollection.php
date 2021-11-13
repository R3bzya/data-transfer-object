<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Collections\Collection as BaseCollection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\Data\Traits\PathTrait;

class ErrorCollection extends BaseCollection implements ErrorCollectionInterface
{
    use PathTrait;

    /**
     * @param string $key
     * @param null $value
     */
    public function set(string $key, $value = null): void
    {
        $this->addItem(ErrorItem::make($key, $this->getArrayFrom($value), Path::make($key)));
    }

    public function addItem(ErrorItemInterface $item): void
    {
        if ($this->has($item->getProperty())) {
            $this->get($item->getProperty())->addMessages($item->getMessages());
        } else {
            parent::set($item->getPath()->get(), $item);
        }
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

    public function merge($collection)
    {
        foreach ($collection->getItems() as $item) {
            if ($collection->hasPath()) {
                $this->addItem($item->setPath($collection->getPath()->with($item->getPath())));
            } else {
                $this->addItem($item);
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return array_map(fn(ErrorItem $item) => $item->toArray(), $this->items());
    }
}
