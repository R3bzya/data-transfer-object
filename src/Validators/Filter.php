<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\Validators\FilterInterface;
use Rbz\DataTransfer\Transfer;
use function array_filter_keys;

class Filter implements FilterInterface
{
    private array $properties;
    private array $rules = [];

    public function setProperties(array $properties): FilterInterface
    {
        $this->properties = $properties;
        return $this;
    }

    public function setRules(array $properties): FilterInterface
    {
        $this->rules = $properties;
        return $this;
    }

    public function getProperties(): array
    {
        return count($this->rules)
            ? array_merge($this->filter(), $this->getIncludes())
            : $this->properties;
    }

    public function transferData(Transfer $transfer): array
    {
        $array = [];
        foreach ($this->getProperties() as $property) {
            $array[$property] = $transfer->getProperty($property);
        }
        return $array;
    }

    public function array(array $data): array
    {
        return array_filter_keys($data, function (string $property) {
            return in_array($property, $this->getProperties());
        });
    }

    public function getIncludes(): array
    {
        return array_filter($this->rules, function (string $rules) {
            return '!' != mb_substr($rules,0,1);
        });
    }

    private function filter(): array
    {
        $prepared = [];
        foreach ($this->properties as $property) {
            if (in_array('!'.$property, $this->rules)) {
                continue;
            }
            if (in_array($property, $this->rules)) {
                $prepared[] = $property;
            }
        }
        return $prepared;
    }
}
