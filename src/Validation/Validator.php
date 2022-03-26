<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Exceptions\ValidationException;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Validation\ValidatorInterface;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Traits\ValidatesPropertiesTrait;

class Validator implements ValidatorInterface
{
    use ValidatesPropertiesTrait,
        ErrorCollectionTrait;

    private array $data;
    private array $rules;

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public static function make(array $data, array $rules): ValidatorInterface
    {
        return new Validator($data, $rules);
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
            $this->errors()->set($property, Messenger::getMessage($property, $rule));
        }
    }

    private function getValue(string $property)
    {
        if (! key_exists($property, $this->data)) {
            throw new ValidationException("Key `{$property}` is undefined");
        }
        return $this->data[$property];
    }
}
