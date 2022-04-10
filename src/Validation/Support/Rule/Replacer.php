<?php

namespace Rbz\Data\Validation\Support\Rule;

use Rbz\Data\Support\Arr;

class Replacer
{
    private array $array;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function byMask(Mask $mask): Replaced
    {
        return $this->replaced($this->replaceByMask($mask));
    }

    private function has(string $key): bool
    {
        return Arr::has($this->array, $key);
    }

    private function replaceByMask(Mask $mask): array
    {
        $result = [];

        foreach ($this->array as $key => $value) {
            if (! $mask->isAsterisk() && ! $this->has($mask->getCurrent()) ) {
                Arr::set($result, $mask->getCurrent(), []);
                continue;
            }

            if (! $mask->isCurrent($key)) {
                continue;
            }

            if (Arr::is($value) && $mask->canShift()) {
                Arr::set($result, $key, (new static($value))->replaceByMask($mask->shift()));
            } else {
                Arr::set($result, $key, []);
            }
        }

        return $result;
    }

    private function replaced(array $replaced): Replaced
    {
        return new Replaced($this->array, $replaced);
    }
}
