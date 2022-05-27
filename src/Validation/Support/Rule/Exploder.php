<?php

namespace Rbz\Data\Validation\Support\Rule;

use Rbz\Data\Components\Path;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;
use Rbz\Data\Validation\Support\Data;

class Exploder
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function explode(array $data, array $rules): array
    {
        return (new static($data))->run($rules);
    }

    public function run(array $rules): array
    {
        $result = [];

        foreach ($rules as $key => $rule) {
            if (Str::notContains($key, Data::ASTERISK)) {
                Arr::set($result, $key, $this->formatRules($rule));
                continue;
            }

            $result = Arr::merge($result, $this->explodeAsterisk(Path::make($key), $this->formatRules($rule)));
        }

        return $result;
    }

    private function formatRules($rules): array
    {
        return Str::is($rules) ? Str::explode($rules, '|') : $rules;
    }

    /**
     * @throws PathException
     */
    public function explodeAsterisk(Path $path, array $rules): array
    {
        if ($path->first()->is(Data::ASTERISK)) {
            return $this->each($this->data, $rules);
        }

        return [$path->first()->get() => static::explode(
            Arr::get($this->data, $path->first()->get(), []), [$path->slice(1)->get() => $rules]
        )];
    }

    private function each(array $data, array $rules): array
    {
        $each = [];
        foreach ($data as $key => $value) {
            $each[$key] = $rules;
        }
        return $each;
    }
}
