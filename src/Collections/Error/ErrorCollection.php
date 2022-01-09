<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Exceptions\CollectionException;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

class ErrorCollection extends Collection implements ErrorCollectionInterface
{
    public function set($key, $value = null)
    {
        $this->assertKey($key);
        return $this->addItem(ErrorItem::make($key, $this->makeArrayFrom($value)));
    }

    public function addItem(ErrorItemInterface $item)
    {
        if ($this->has($item->getPath()->get())) {
            $this->get($item->getPath()->get())->addMessages($item->getMessages());
        } else {
            parent::set($item->getPath()->get(), $item);
        }
        return $this;
    }

    public function getFirstMessage(string $property = null): ?string
    {
        /** @var ErrorItemInterface $error */
        if ($error = $property ? $this->get($property) : $this->first()) {
            return $error->getMessage();
        }
        return null;
    }

    public function assertKey($key): void
    {
        if (! is_string($key)) {
            throw new CollectionException('Key type must be a string, ' . gettype($key) . ' given');
        }
    }

    public function withPathAtTheBeginning(PathInterface $path)
    {
        return $this->clone()->mapWithKeys(function (ErrorItemInterface $item) use ($path) {
            $path = $path->with($item->getPath());
            return [$path->get() => $item->clone()->setPath($path)];
        });
    }

    public function countMessages(): int
    {
        $result = 0;
        $this->each(function (ErrorItemInterface $item) use (&$result) {
            $result += $item->count();
        });
        return $result;
    }
}
