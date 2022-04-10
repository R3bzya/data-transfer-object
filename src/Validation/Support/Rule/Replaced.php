<?php

namespace Rbz\Data\Validation\Support\Rule;

use Rbz\Data\Support\Arr;

class Replaced
{
    private array $raw;
    private array $data;
    private array $rules = [];

    /**
     * @param array $raw
     * @param array $data
     */
    public function __construct(array $raw, array $data)
    {
        $this->raw = $raw;
        $this->data = $data;
    }

    public function getRaw(): array
    {
        return $this->fill($this->raw);
    }

    public function getDot(): array
    {
        return $this->fill(Arr::dot($this->data));
    }

    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    private function fill(array $data): array
    {
        if (Arr::isEmpty($this->rules)) {
            return $data;
        }
        return Arr::collect($data)->map(fn($value) => $this->rules)->toArray();
    }
}
