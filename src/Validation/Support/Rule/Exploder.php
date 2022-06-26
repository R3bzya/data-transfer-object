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
    
    public static function explode(array $data, array $rules): array
    {
        return (new static($data))->execute($rules);
    }
    
    public function execute(array $rules): array
    {
        $result = [];
        
        foreach ($rules as $key => $keyRules) {
            if (Str::notContains($key, '*')) {
                $result[$key] = $this->formatRules($keyRules);
            } else {
                $result = Arr::merge($result, $this->explodeAsterisk($key, $this->formatRules($keyRules)));
            }
        }
        
        return $result;
    }
    
    private function formatRules($rules): array
    {
        return Str::is($rules) ? Str::explode($rules, '|') : $rules;
    }
    
    private function explodeAsterisk(string $key, array $rules): array
    {
        $result = [];
        
        foreach ($this->data as $dataKey => $value) {
            if (Str::cmp($key, '*')) {
                $result[$dataKey] = $rules;
                continue;
            }
    
            $path = Str::explode($key);
            $previousKey = Str::cmp($path[0], '*') ? $dataKey : $path[0];
            unset($path[0]);
            $path = Arr::implode($path);
            
            if (Arr::is($value)) {
                $result = Arr::merge($result, $this->explodeArray($value, $path, $previousKey, $rules));
                continue;
            }
    
            if (Str::startWith($key, '*') || Str::startWith($key, $dataKey)) {
                $result[$dataKey] = ['array'];
            }
        }
        
        return $result;
    }
    
    private static function addPreviousKey(array $keys, string $previousKey): array
    {
        return Arr::collect($keys)->mapWithKeys(fn(array $value, string $key) => [$previousKey . '.' . $key => $value])->toArray();
    }
    
    private function explodeArray(array $data, string $key, string $previousKey, array $rules): array
    {
        return static::addPreviousKey(static::explode($data, [$key => $rules]), $previousKey);
    }
}
