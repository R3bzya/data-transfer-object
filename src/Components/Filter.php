<?php

namespace Rbz\Data\Components;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Components\FilterInterface;
use Rbz\Data\Interfaces\Components\PathInterface;
use Rbz\Data\Interfaces\TransferInterface;

class Filter implements FilterInterface
{
    private array $properties;

    /** @var string[] */
    private array $exclude = [];

    /** @var string[] */
    private array $include = [];
    private PathInterface $path;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public static function make(array $properties): FilterInterface
    {
        return new self($properties);
    }

    public static function separator(): string
    {
        return '!';
    }

    public static function getSeparator(): string
    {
        return self::separator();
    }

    public function filterTransfer(TransferInterface $transfer): array
    {
        $array = [];
        foreach ($this->apply() as $property) {
            $array[$property] = $transfer->getProperty($property);
        }
        return $array;
    }

    public function filterArray(array $data): array
    {
        return Collection::make($data)->filter(fn($value, $property) => in_array($property, $this->apply()))->toArray();
    }

    public function apply(): array
    {
        if ($this->hasInclude()) {
            return $this->filterNotInclude($this->properties());
        }
        return $this->filterExclude($this->properties());
    }

    public function getExcludeFrom(array $data): array
    {
        return array_map(fn(string $rule) => mb_substr($rule, 1), $this->getRawExcludeFrom($data));
    }

    public function getRawExcludeFrom(array $data): array
    {
        return array_filter($data, fn(string $rule) => $this->isExclude($rule));
    }

    public function getIncludeFrom(array $data): array
    {
        return array_filter($data, fn(string $rule) => $this->isInclude($rule));
    }

    public function isInclude(string $rule): bool
    {
        return ! str_starts_with($rule, self::separator());
    }

    public function isExclude(string $rule): bool
    {
        return str_starts_with($rule, self::separator());
    }

    public function hasInclude(): bool
    {
        return count($this->include());
    }

    public function hasExclude(): bool
    {
        return count($this->exclude());
    }

    public function filterNotInclude(array $properties): array
    {
        return array_filter($properties, fn(string $property) => $this->inInclude($property));
    }

    public function filterExclude(array $properties): array
    {
        return array_filter($properties, fn(string $property) => ! $this->inExclude($property));
    }

    public function include(): array
    {
        return $this->include;
    }

    public function exclude(): array
    {
        return $this->exclude;
    }

    public function properties(): array
    {
        return $this->properties;
    }

    public function inInclude(string $property): bool
    {
        return in_array($property, $this->include());
    }

    public function inExclude(string $property): bool
    {
        return in_array($property, $this->exclude());
    }

    public function toArray(): array
    {
        return $this->properties();
    }

    public function getRules(): array
    {
        return array_merge($this->include(), $this->exclude());
    }

    public function getInclude(): array
    {
        return $this->include();
    }

    public function getExclude(): array
    {
        return $this->exclude();
    }

    public function getProperties(): array
    {
        return $this->properties();
    }

    public function setRules(array $rules): FilterInterface
    {
        $this->exclude = $this->getExcludeFrom($rules);
        $this->include = $this->getIncludeFrom($rules);
        return $this;
    }

    public function setPath(PathInterface $path): void
    {
        if ($this->hasPath()) {
            $this->path = $this->path->with($path);
        } else {
            $this->path = $path;
        }
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }

    public function hasPath(): bool
    {
        return isset($this->path);
    }
}
