<?php

namespace Rbz\Data\Helpers;

use Rbz\Data\Components\Filter;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Interfaces\TransferInterface;

class ValidateHelper
{
    private TransferInterface $transfer;

    public function __construct(TransferInterface $transfer)
    {
        $this->transfer = $transfer;
    }

    public function parse(array $properties): array
    {
        if ($this->transfer->hasPath()) {
            return $this->getTransferProperties($properties, $this->transfer);
        }
        return array_filter($properties, fn(string $property) => $this->transferHasProperty($property));
    }

    public function transferHasProperty(string $property): bool
    {
        if (Filter::isInclude($property)) {
            return $this->transfer->hasProperty($property);
        } elseif (Filter::isExclude($property)) {
            return $this->transfer->hasProperty(Filter::makeRaw($property));
        }
        return false;
    }

    public function findInclude(array $properties, PathInterface $path): bool
    {
        foreach ($properties as $property) {
            if ($path->equal(Path::make($property))) {
                return true;
            }
        }
        return false;
    }

    public function hasExcludeProperties(array $properties, PathInterface $path): bool
    {
        foreach ($properties as $property) {
            if ($path->equal($this->normalizePath(Path::make($property)))) {
                return true;
            }
        }
        return false;
    }

    private function normalizePath(PathInterface $path): PathInterface
    {
        return Path::make(Path::makeString(array_map(fn(string $sector) => Filter::makeRaw($sector), $path->toArray())));
    }

    private function getTransferProperties(array $properties, TransferInterface $transfer): array
    {
        if ($this->findInclude($properties, $this->transfer->getPath())) {
            return $this->transfer->getProperties();
        } elseif ($this->hasExclude($properties, $transfer->getPath())) {
            return array_map(fn(string $property) => Filter::makeExclude($property), $transfer->getProperties());
        } elseif ($filtered = $this->filterPropertiesByPath($properties, $transfer->getPath())) {
            return array_map(fn(string $property) => Path::make($property)->last()->get(), $filtered);
        }
        return $properties;
    }

    private function filterPropertiesByPath(array $properties, PathInterface $path): array
    {
        return array_filter($properties, fn(string $property) => str_starts_with($property, $path->last()->get()));
    }

    private function hasExcludeTransferEntry(array $properties, PathInterface $path): bool
    {
        foreach ($properties as $property) {
            if (str_starts_with($property, Filter::makeExclude($path->last()->get()))) {
                return true;
            }
        }
        return false;
    }

    private function hasExclude(array $properties, PathInterface $path): bool
    {
        return $this->hasExcludeProperties($properties, $path) || $this->hasExcludeTransferEntry($properties, $path);
    }
}
