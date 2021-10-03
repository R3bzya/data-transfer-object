<?php

namespace Rbz\DataTransfer;

use Illuminate\Support\Facades\Validator as CustomValidatorFactory;
use Illuminate\Support\Str;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Factory as ValidatorFactory;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait;

    abstract public function rules(): array;

    public function load(array $data): bool
    {
        if (! empty($data)) {
            $this->setProperties($data);
        }
        return $this->isLoad(array_keys($data));
    }

    public function validate(array $attributes = []): bool
    {
        if ($this->errors()->isNotEmpty()) {
            return false;
        }
        $validation = $this->isLoad($attributes ?: $this->getProperties());
        if ($validation && $this->rules()) {
            /** ToDo это работать не будет $attributes ?: $this->getProperties() */
            return $this->validateCustom($attributes ?: $this->getProperties(), $this->rules());
        }
        return $validation;
    }

    public function isLoad(array $attributes): bool
    {
        $errors = ValidatorFactory::makeIsLoad($this, $attributes)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    public function validateCustom(array $data, array $rules): bool
    {
        $messageBag = CustomValidatorFactory::make($data, $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
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

    public function getTransferName(): string
    {
        return get_class_name($this);
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }

    /** @deprecated  */
    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->errors()->getFirstMessage($attribute);
    }

    /** @deprecated  */
    public function getErrorCount(): int
    {
        return $this->errors()->count();
    }

    public function setProperty(string $property, $value): void
    {
        try {
            parent::setProperty($property, $value);
        } catch (Throwable $e) {}
    }
}
