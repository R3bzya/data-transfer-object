<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Exceptions\ValidationException;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Validation\ValidatorInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Traits\ErrorBagTrait;
use Rbz\Data\Traits\ValidatesPropertiesTrait;
use Rbz\Data\Validation\Support\Data;
use Rbz\Data\Validation\Support\Messenger;
use Rbz\Data\Validation\Support\Rule\Exploder;

class Validator implements ValidatorInterface
{
    use ValidatesPropertiesTrait,
        ErrorBagTrait;

    private array $data;
    private array $rules;

    public function __construct(array $data, array $rules)
    {
        $this->data = Data::encode($data);
        $this->rules = Exploder::explode($this->data, $rules);
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

    private function validateProperty(string $property, string $rule)
    {
        if (! $this->validateRule($rule, $property, $this->getValue($property))) {
            $this->errors()->set($property, Messenger::getMessage($property, $rule));
        }
    }

    private function getValue(string $property)
    {
        return Arr::get($this->data, $property);
    }
}
