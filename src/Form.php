<?php

namespace Rbz\Forms;

use Rbz\Forms\Errors\Collection\ErrorCollection;
use Rbz\Forms\Errors\Collection\ErrorItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\FromInterface;

abstract class Form extends Attributes
    implements FromInterface
{
    private ErrorCollection $errors;

    abstract public function rules(): array;

    public function load(array $data): bool
    {
        if (empty($data) || ! $this->setAttributes($data)) {
            $this->setError($this->getFormName(), ErrorMessage::getMessageNotLoad($this->getFormName()));
            return false;
        }

        return true;
    }

    public function validate(): bool
    {
        if ($rules = static::rules()) {
            return $this->customValidate($rules);
        } else {
            return $this->isAvailableAttributes();
        }
    }

    private function customValidate(array $rules): bool
    {
        $messageBag = Validator::make($this->toArray(), $rules)->getMessageBag();

        foreach ($messageBag->toArray() as $attribute => $errors) {
            foreach ($errors as $error) {
                $this->setError($attribute, $error);
            }
        }

        return $messageBag->isEmpty();
    }

    public function isAvailableAttributes(): bool
    {
        foreach ($this->getAttributes() as $attribute) {
            if (! $this->isAvailableAttribute($attribute)) {
                $this->setError($attribute, ErrorMessage::getMessageIsNotSet($attribute));
            }
        }

        return parent::isAvailableAttributes();
    }

    protected function toCamelCase(string $value): string
    {
        return Str::camel($value);
    }

    public function toCamelCaseKeys(array $data): array
    {
        $camelCaseAttributes = [];
        foreach ($data as $attribute => $value) {
            $camelCaseAttributes[$this->toCamelCase($attribute)] = $value;
        }
        return $camelCaseAttributes;
    }

    public function getErrors(): ErrorCollection
    {
        if (! isset($this->errors)) {
            $this->errors = new ErrorCollection();
        }
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }

    public function getFormName(): string
    {
        return $this->toCamelCase(get_class_name($this));
    }

    public function setError(string $attribute, string $message): void
    {
        $this->getErrors()->add(new ErrorItem($attribute, $message));
    }
}
