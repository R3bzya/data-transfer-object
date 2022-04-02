<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Exceptions\ValidationException;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Validation\ValidatorInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Traits\ValidatesPropertiesTrait;
use Rbz\Data\Validation\Support\Data;

class Validator implements ValidatorInterface
{
    use ValidatesPropertiesTrait,
        ErrorCollectionTrait;

    private array $data;
    private array $rules;

    public function __construct(array $data, array $rules)
    {
        $this->data = Data::encode($data);
        $this->rules = $rules;
    }

    public static function make(array $data, array $rules): ValidatorInterface
    {
        return new static($data, $rules);
    }

    public function validate(): bool
    {
        foreach ($this->rules as $property => $rules) {
            foreach ($rules as $rule) {
                $this->validateProperty($property, $rule);
            }
        }

        return ! $this->hasErrors();
    }

    public function validated(): CollectionInterface
    {
        throw new ValidationException('Implement validated() method.');
    }

    private function validateProperty(string $property, $rule)
    {
        if (! $this->{"validate{$rule}"}($property, $this->getValue($property))) {
            $this->errors()->set($property, (new Messenger())->getMessage($property, $rule));
        }
    }

    private function getValue(string $property)
    {
        return Arr::get($this->data, $property);
    }
}
