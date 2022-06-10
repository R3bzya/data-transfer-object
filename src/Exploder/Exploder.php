<?php

namespace Rbz\Data\Exploder;

use Rbz\Data\Components\Path;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Exploder
{
    public function explode(array $array, array $rules): array
    {
        $result = [];

        foreach ($rules as $path => $pathRules) {
            $path = Path::make($path);

            if ($path->isNotContains('*')) {
                $result[$path->get()] = $this->formatRules($pathRules);
            } else {
                $result = Arr::merge($result, $this->explodeAsterisk($array, $path, $this->formatRules($pathRules)));
            }
        }

        dd($result);

        return $result;
    }

    /**
     * @param string|array $rules
     * @return array
     */
    public function formatRules($rules): array
    {
        return Arr::is($rules) ? $rules : Str::explode($rules, '|');
    }

    private function explodeAsterisk(array $array, Path $path, array $rules): array
    {
        $result = [];

        if (! $path->first()->isString('*')) {
            $data = Arr::get($array, $path->first()->get());


            if (Arr::is($data)) {
                foreach ($data as $key => $value) {
                    $result[$path->first()->get()][$key] = $this->explodeAsterisk($data, $path->slice(1), $rules);
                }
            } else {
                $result[$path->get()] = $rules;
            }

        } else {
            foreach ($array as $key => $value) {
                if (Arr::is($value) && $path->isContains('*')) {
                    $result[$key] = $this->explodeAsterisk($value, $path->slice(1), $rules);
                } else {
                    $result[$path->get()] = $rules;
                }
            }

        }

        return $result;
    }
}
