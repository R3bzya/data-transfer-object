<?php

namespace Rbz\Data\Support\Errors;

use Rbz\Data\Interfaces\Errors\ErrorBagInterface;
use Rbz\Data\Interfaces\Errors\ErrorInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Support\Arr;

class ErrorBag implements ErrorBagInterface
{
    /**
     * @var array|ErrorInterface[]
     */
    private array $items = [];

    public static function make(): ErrorBagInterface
    {
        return new self();
    }

    public function get(string $key)
    {
        return $this->toCollection()->get($key);
    }

    public function has(string $key): bool
    {
        return $this->toCollection()->has($key);
    }

    public function set(string $key, $value = null): ?ErrorBagInterface
    {
        return $this->addItem(Error::make($key, Arr::make($value)));
    }

    public function addItem(ErrorInterface $item)
    {
        if ($this->has($item->getPath()->get())) {
            $this->get($item->getPath()->get())->addMessages($item->getMessages());
        } else {
            Arr::set($this->items, $item->getPath()->get(), $item);
        }
        return $this;
    }

    public function getFirstMessage(string $property = null): ?string
    {
        /** @var ErrorInterface $error */
        if ($error = is_null($property) ? Arr::first($this->items()) : $this->get($property)) {
            return $error->getMessage();
        }
        return null;
    }

    public function withPathAtTheBeginning(PathInterface $path): ErrorBagInterface
    {
        $bag = new self();
        $bag->items = Arr::collect($this->items())->mapWithKeys(function (ErrorInterface $item) use ($path) {
            $path = $path->with($item->getPath());
            return [$path->get() => $item->clone()->setPath($path)];
        })->toArray();
        return $bag;
    }

    public function countMessages(): int
    {
        $count = 0;
        $this->toCollection()->each(function (ErrorInterface $item) use (&$count) {
            $count += $item->count();
        });
        return $count;
    }

    public function count(): int
    {
        return $this->toCollection()->count();
    }

    public function isEmpty(): bool
    {
        return $this->toCollection()->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function clone(): ErrorBagInterface
    {
        return clone $this;
    }

    private function items(): array
    {
        return $this->items;
    }

    public function clear(): void
    {
        Arr::clear($this->items);
    }

    public function replace(ErrorBagInterface $bag)
    {
        $this->items = $bag->toArray();
        return $this;
    }

    public function merge(ErrorBagInterface $bag)
    {
        $this->items = Arr::merge($this->items(), $bag->toArray());
        return $this;
    }

    public function with(ErrorBagInterface $bag)
    {
        return $this->clone()->merge($bag);
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function first(): ?ErrorInterface
    {
        return $this->toCollection()->first();
    }

    public function getMessages(): array
    {
        return $this->toCollection()
            ->mapWithKeys(fn(Error $error) => [$error->getProperty() => $error->getMessages()])
            ->toArray();
    }

    public function toCollection(): CollectionInterface
    {
        return Arr::collect($this->items());
    }
}
