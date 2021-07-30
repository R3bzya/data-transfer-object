<?php

namespace Rbz\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rbz\Forms\Errors\Collection\ErrorItem;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\FromInterface;

abstract class Form extends FormErrors
    implements FromInterface
{
    abstract public function rules(): array;

    public function load(array $data): bool
    {
        if (empty($data)) {
            $this->errors()->add($this->getFormName(), ErrorMessage::notLoad($this->getFormName()));
            return false;
        }

        return $this->setAttributes($data);
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
            $this->errors()->addItem(new ErrorItem($attribute, $messages));
        }
        return $messageBag->isEmpty();
    }

    public function validateAttributes(array $attributes): bool
    {
        $validate = true;
        foreach ($attributes as $attribute) {
            $validate = $this->validateAttribute($attribute) && $validate;
        }
        return $validate;
    }

    public function validateAttribute(string $attribute): bool
    {
        if (! $this->hasAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::undefinedProperty($attribute));
            return false;
        }

        if (! $this->isSetAttribute($attribute) && ! $this->isNullAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::isNotSet($attribute));
            return false;
        }

        return true;
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
