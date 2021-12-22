<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\Data\Traits\PathTrait;

class ErrorCollection extends Collection implements ErrorCollectionInterface
{
    use PathTrait;

    public function set(string $key, $value = null)
    {
        return $this->addItem(ErrorItem::make($key, $this->makeArrayFrom($value), Path::make($key)));
    }

    public function addItem(ErrorItemInterface $item)
    {
        if ($this->has($item->getProperty())) {
            $this->get($item->getProperty())->addMessages($item->getMessages());
        } else {
            parent::set($item->getPath()->get(), $item);
        }
        return $this;
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
        /** @var ErrorItemInterface $item */
        foreach ($collection->getItems() as $item) {
            if ($collection->hasPath()) {
                $this->addItem($item->clone()->setPath($collection->getPath()->with($item->getPath())));
            } else {
                $this->addItem($item);
            }
        }
        return $this;
    }
}
