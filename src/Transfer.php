<?php

namespace Rbz\DataTransfer;

use DomainException;
use Illuminate\Contracts\Support\Arrayable;
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

    public function rules(): array
    {
        return [];
    }

    /**
     * @throws DomainException
     */
    public function load($data): bool
    {
        $data = $this->normalizeData($data);
        $this->setProperties($data);
        return $this->isLoad(array_keys($data) ?: $this->getProperties());
    }

    public function validate(array $attributes = []): bool
    {
        if ($this->errors()->isNotEmpty()) {
            return false;
        }
        $validation = $this->isLoad($attributes ?: $this->getProperties());
        if ($validation && $this->rules()) {
            /** ToDo не уверен в этих двух методах getTransferData, getFilteredRules */
            return $this->validateCustom(
                $this->getTransferData($attributes ?: $this->getProperties()),
                $this->getFilteredRules($this->rules(), $attributes ?: $this->getProperties())
            );
        }
        return $validation;
    }

    public function getTransferData(array $properties): array
    {
        $tests = [];
        foreach ($this->getProperties() as $property) {
            if (in_array($property, $properties)) {
                $tests[$property] = $this->getProperty($property);
            }
        }
        return $tests;
    }

    public function getFilteredRules(array $rules, array $attributes): array
    {
        return array_filter_keys($rules, function (string $property) use ($attributes) {
            return in_array($property, $attributes);
        });
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

    public function setProperty(string $property, $value): void
    {
        try {
            parent::setProperty($property, $value);
        } catch (Throwable $e) {}
    }

    /**
     * @param array|Arrayable $value
     * @return array
     * @throws DomainException
     */
    public function normalizeData($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if ($value instanceof Arrayable) {
            return $value->toArray();
        }
        throw new DomainException('The data must be an array or an Arrayable instance');
    }
}
