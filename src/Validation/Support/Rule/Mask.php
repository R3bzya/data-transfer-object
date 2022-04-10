<?php

namespace Rbz\Data\Validation\Support\Rule;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Mask
{
    private array $mask;
    private string $current;

    /**
     * @param array|string $mask
     */
    public function __construct($mask)
    {
        $this->mask = Arr::is($mask) ? $mask : Str::explode($mask);
    }

    public function isCurrent($key): bool
    {
        return $this->isAsterisk() || Str::cpm($this->getCurrent(), $key);
    }

    public function shift(): Mask
    {
        return new static(Arr::slice($this->mask, 1));
    }

    public function getCurrent(): ?string
    {
        if (! isset($this->current)) {
            $this->current = Arr::first($this->mask);
        }
        return $this->current;
    }

    public function canShift(): bool
    {
        return Arr::countGt($this->mask, 1);
    }

    public function isAsterisk(): bool
    {
        return Str::cpm($this->getCurrent(), '*');
    }
}
