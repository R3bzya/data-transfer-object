<?php

namespace Rbz\DataTransfer;

use DomainException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator as CustomValidatorFactory;
use Illuminate\Support\Str;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\FilterFactory;
use Rbz\DataTransfer\Validators\Validator;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait;

    protected string $adapter;

    public function __get($name)
    {
        if (isset($this->adapter) && method_exists($this->adapter, $name)) {
            return call_user_func([$this->adapter, $name], $this);
        }
        return parent::__get($name);
    }

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
        return $this->validateIsLoad(array_keys($data) ?: $this->getProperties());
    }

    public function filterFactory(): FilterFactory
    {
        return new FilterFactory();
    }

    public function validate(array $properties = []): bool
    {
        $filter = $this->filterFactory()->make($this->getProperties(), $properties);
        if (! $this->validateHas($filter->getRules()) || $this->errors()->isNotEmpty()) {
            return false;
        }
        $validation = $this->validateIsLoad($filter->filtered());
        if ($validation && $this->rules()) {
            return $this->validateCustom($filter->filterTransfer($this), $filter->filterArrayKeys($this->rules()));
        }
        return $validation;
    }

    public function validateHas(array $properties): bool
    {
        $errors = Validator::makeHas($this, $properties)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    public function validateIsLoad(array $properties): bool
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
