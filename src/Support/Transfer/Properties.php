<?php

namespace Rbz\Data\Support\Transfer;

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
    
    public function get(array $internalTransfers = []): array
    {
        if (Arr::isEmpty($internalTransfers)) {
            return Arr::filter($this->items, fn($value) => Arr::isNot($value));
        }
        return $this->filterBy($internalTransfers);
    }
    
    private function filterBy(array $internalTransfers): array
    {
        $result = [];
        foreach ($internalTransfers as $internalTransfer) {
            if (Arr::has($this->items, '!'.$internalTransfer)) {
                Arr::set($result, $internalTransfer, ['__toExclude__']);
            } else {
                Arr::set($result, $internalTransfer, Arr::get($this->items, $internalTransfer, []));
            }
        }
        return $result;
    }
}