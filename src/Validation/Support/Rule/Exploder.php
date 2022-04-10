<?php

namespace Rbz\Data\Validation\Support\Rule;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Exploder
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function explode(array $rules): array
    {
        $result = [];

        foreach ($rules as $key => $rule) {
            if (Str::notContains($key, '*')) {
                Arr::set($result, $key, $this->formatRules($rule));
                continue;
            }

            $result = Arr::merge(
                $result,
                (new Replacer($this->data))->byMask(new Mask($key))->setRules($this->formatRules($rule))->getDot()
            );
        }

        return $result;
    }

    private function formatRules($rules): array
    {
        return Str::is($rules) ? Str::explode($rules, '|') : $rules;
    }
}
