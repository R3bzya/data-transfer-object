<?php

namespace Rbz\Data\Validation\Support;

use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class RuleExploder
{
    private array $data;

    private array $currentRules;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function explode(array $rules): array
    {
        $result = [];

        foreach ($rules as $key => $rule) {
            $this->setCurrentRules($rule);

            if (Str::contains($key, '*')) {
                $result = Arr::merge($result, $this->explodeAsterisk(Path::make($key)));
            } else {
                Arr::set($result, $key, $this->getCurrentRules());
            }
        }

        return $result;
    }

    private function explodeAsterisk(PathInterface $key): array
    {
        return Arr::collect(Arr::dot($this->data))
            ->keys()
            ->map(fn(string $key) => Path::make($key))
            ->filter(fn(PathInterface $path) => $key->first()->is($path->first()))
            ->mapWithKeys(fn(PathInterface $path) => [$this->makeRuleKey($key, $path) => $this->getCurrentRules()])
            ->toArray();
    }

    /**
     * @param array|string $rules
     * @return void
     */
    private function setCurrentRules($rules): void
    {
        $this->currentRules = Str::is($rules)
            ? Str::explode($rules, '|')
            : $rules;
    }

    private function getCurrentRules(): array
    {
        return $this->currentRules;
    }

    private function makeRuleKey(PathInterface $key, PathInterface $dataKey): string
    {
        $sliced = $dataKey->sliceBy($key->first()->get());
        $key = $key->clone()->replace('*', $sliced->first()->get(), 1);

        if (Str::contains($key->get(), '*')) {
            return $this->makeRuleKey($key, $sliced);
        }

        return $key->get();
    }
}
