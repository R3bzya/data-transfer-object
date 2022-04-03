<?php

namespace Rbz\Data\Errors;

use Rbz\Data\Interfaces\Errors\ErrorBagInterface;
use Rbz\Data\Interfaces\Errors\ErrorInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
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
        return Arr::get($this->items(), $key);
    }

    public function has(string $key): bool
    {
        return Arr::has($this->items(), $key);
    }

    public function set(string $key, $value = null)
    {
        return $this->addItem(Error::make($key, Arr::make($value)));
    }

    public function addItem(ErrorInterface $item)
    {
        if (Arr::has($this->items(), $item->getPath()->get())) {
            $this->get($item->getPath()->get())->addMessages($item->getMessages());
        } else {
            $this->items[$item->getPath()->get()] = $item;
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

    public function withPathAtTheBeginning(PathInterface $path)
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
        Arr::collect($this->items())->each(function (ErrorInterface $item) use (&$count) {
            $count += $item->count();
        });
        return $count;
    }

    public function count(): int
    {
        return Arr::count($this->items());
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
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

    public function toArray(): array
    {
        return $this->items();
    }

    public function with(ErrorBagInterface $bag)
    {
        return $this->clone()->merge($bag);
    }

    public function first()
    {
        return Arr::first($this->items());
    }
}
