<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

class PropertyHelper
{
    private array $items = [];

    public function __construct(array $data)
    {
        foreach ($data as $path) {
            $this->items = array_merge($this->items, $this->resolve(Path::make($path)));
        }
    }

    private function resolve(PathInterface $path): array
    {
        $items = [];
        $path->isInternal()
            ? $items[$path->geFirstSection()->get()] = $this->resolve($path->slice(1))
            : $items[] = $path->get();
        return $items;
    }

    public function get(string $key = null): array
    {
        if (is_null($key)) {
            return $this->getCurrents();
        }
        return $this->items[$key] ?? [];
    }

    private function getCurrents(): array
    {
        return Collection::make($this->items)->filter(fn($value) => ! is_array($value))->toArray();
    }
}
