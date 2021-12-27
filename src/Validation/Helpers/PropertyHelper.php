<?php

namespace Rbz\Data\Validation\Helpers;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

class PropertyHelper
{
    private array $items = [];

    /**
     * @param array $data
     * @throws PathException
     */
    public function __construct(array $data)
    {
        foreach ($data as $path) {
            $this->items = array_merge($this->items, $this->explode(Path::make($path)));
        }
    }

    private function explode(PathInterface $path): array
    {
        $items = [];
        $path->isNested()
            ? $items[$path->firstSection()->get()] = $this->explode($path->slice(1))
            : $items[] = $path->get();
        return $items;
    }

    public function get(string $key = null): array
    {
        if (is_null($key)) {
            return $this->getCurrents();
        }
        if ($this->has($key)) {
            return $this->items[$key];
        }
        if ($this->has('!'.$key)) {
            return ['__toExclude__'];
        }
        return [];
    }

    private function getCurrents(): array
    {
        return Collection::make($this->items)->filter(fn($value) => ! is_array($value))->toArray();
    }

    private function has(string $key): bool
    {
        return key_exists($key, $this->items);
    }
}
