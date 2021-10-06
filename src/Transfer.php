<?php

namespace Rbz\DataTransfer;

use DomainException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator as CustomValidatorFactory;
use Illuminate\Support\Str;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Traits\FilterTrait;
use Rbz\DataTransfer\Validators\Validator;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait,
        FilterTrait;

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

    public function validate(array $properties = []): bool
    {
        if ($this->errors()->isNotEmpty()) {
            return false;
        }
        $filter = $this->makeFilter($this->getProperties(), $properties);
        $validation = $this->isLoad($filter->getProperties());
        if ($validation && $this->rules()) {
            return $this->validateCustom($filter->transferData($this), $filter->array($this->rules()));
        }
        return $validation;
    }

    public function isLoad(array $properties): bool
    {
        $errors = Validator::makeIsLoad($this, $properties)->getErrors();
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
