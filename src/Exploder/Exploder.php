<?php

namespace Rbz\Data\Exploder;

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
                Arr::set($result, $key, $this->formatRules($keyRules));
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
            $path = Str::explode($key);
            $previousKey = $path[0];
            unset($path[0]);
            
            if (Str::cmp($key, '*')) {
                $result[$dataKey] = $rules;
                continue;
            }
            
    
            if (Str::startWith($key, '*')) {
                
                if (is_array($value)) {
                    $result = Arr::merge($result, static::addPreviousKey(static::explode($value, [Arr::implode($path) => $rules]), $dataKey));
                } else {
                    if (Str::startWith(Arr::implode($path), '*')) {
                        $result[$dataKey] = ['array'];
                    } else {
                        $result[$dataKey . '.' . Arr::implode($path)] = $rules;
                    }
                }
                
                continue;
            }
            
            if (Str::startWith($key, $dataKey)) {
                if (is_array($value)) {
                    $result = Arr::merge($result, static::addPreviousKey(static::explode($value, [Arr::implode($path) => $rules]), $previousKey));
                } else {
                    $result[$dataKey] = ['array'];
                }
            }
        }
        
        return $result;
    }
    
    private static function addPreviousKey(array $keys, string $previousKey): array
    {
        $result = [];
        
        foreach ($keys as $key => $value) {
            $result[$previousKey . '.' . $key] = $value;
        }
        
        return $result;
    }
}
