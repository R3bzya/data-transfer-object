<?php

namespace Rbz\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rbz\Forms\Errors\Collection\ErrorCollection;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\FromInterface;

abstract class Form extends FormErrors
    implements FromInterface
{
    abstract public function rules(): array;

    public function load(array $data): bool
    {
        $errors = new ErrorCollection();
        if (empty($data) || ! $this->setAttributes($data)) {
            $errors->add($this->getFormName(), ErrorMessage::getNotLoad($this->getFormName()));
        }

        $this->setErrors($this->errors()->with($errors));
        return $errors->isEmpty();
    }

    public function validate(): bool
    {
        $validate = $this->validateAttributes($this->getAttributes());
        if ($validate && $rules = static::rules()) {
            return $this->validateRules($rules);
        }
        return $validate;
    }

    public function validateRules(array $rules): bool
    {
        $messageBag = Validator::make($this->toArray(), $rules)->getMessageBag();
        foreach ($messageBag->toArray() as $attribute => $messages) {
            $this->errors()->add($attribute, $messages);
        }
        return $messageBag->isEmpty();
    }

    public function validateAttributes(array $attributes): bool
    {
        $errors = new ErrorCollection();
        foreach ($attributes as $attribute) {
            if (! $this->validateAttribute($attribute)) {
                $errors->add($attribute, ErrorMessage::getIsNotSet($attribute));
            }
        }

        $this->setErrors($this->errors()->with($errors));
        return $errors->isEmpty();
    }

    public function validateAttribute(string $attribute): bool
    {
        return $this->isSetAttribute($attribute) || $this->isNullAttribute($attribute);
    }

    protected function toCamelCase(string $value): string
    {
        return Str::camel($value);
    }

    public function toCamelCaseKeys(array $data): array
    {
        $camelCaseAttributes = [];
        foreach ($data as $attribute => $value) {
            $camelCaseAttributes[$this->toCamelCase($attribute)] = is_array($value)
                ? $this->toCamelCaseKeys($value)
                : $value;
        }
        return $camelCaseAttributes;
    }

    public function getFormName(): string
    {
        return $this->toCamelCase(get_class_name($this));
    }
}
