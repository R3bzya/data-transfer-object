<?php

namespace Rbz\Data\Support\Transfer;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Support\Arr;

class Properties
{
    private array $items = [];

    /**
     * @param array $data
     * @throws PathException
     */
    public function __construct(array $data)
    {
        foreach ($data as $path) {
            $this->items = Arr::merge($this->items, $this->explode(Path::make($path)));
        }
    }

    private function explode(PathInterface $path): array
    {
        $items = [];
        $path->isNested()
            ? $items[$path->first()->get()] = $this->explode($path->slice(1))
            : $items[] = $path->get();
        return $items;
    }

    public function get(string $key = null): array
    {
        if (is_null($key)) {
            return $this->getCurrents();
        }
        if (Arr::has($this->items, $key)) {
            return $this->items[$key];
        }
        if (Arr::has($this->items,'!'.$key)) {
            return ['__toExclude__'];
        }
        return [];
    }

    private function getCurrents(): array
    {
        return Collection::make($this->items)->filter(fn($value) => Arr::isNot($value))->toArray();
    }
}
