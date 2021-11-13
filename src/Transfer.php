<?php

namespace Rbz\Data;

use Illuminate\Support\Facades\Validator as CustomValidatorFactory;
use Illuminate\Support\Str;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Traits\CollectorTrait;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Traits\FilterTrait;
use Rbz\Data\Traits\PathTrait;
use Rbz\Data\Validators\Validator;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait,
        CollectorTrait,
        FilterTrait,
        PathTrait;

    public static function make($data = []): TransferInterface
    {
        $transfer = new static();
        if (! empty($data)) {
            $transfer->load($data);
        }
        return $transfer;
    }

    public function rules(): array
    {
        return [];
    }

    public function load($data): bool
    {
        $this->errors()->clear();
        $data = Collection::make($data)->toArray();
        $this->setProperties($data);
        return $this->validateIsLoad($this->getOnlyTransferProperties($data)->keys()->toArray() ?: $this->getProperties()->toArray());
    }

    public function validate(array $properties = []): bool
    {
        $this->errors()->clear();
        $filter = $this->filter()->setRules($properties);

        if (! $this->validateHas($filter->apply()) || $this->errors()->isNotEmpty()) {
            return false;
        }

        $validation = $this->validateIsLoad($filter->apply());
        if ($validation && $this->rules()) {
            return $this->validateCustom($filter->filterTransfer($this), $filter->filterArray($this->rules()));
        }
        return $validation;
    }

    /** ToDo какие-то дубли */
    public function validateHas(array $properties): bool
    {
        $errors = Validator::makeHas($this, $properties)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    /** ToDo какие-то дубли */
    public function validateIsLoad(array $properties): bool
    {
        $errors = Validator::makeIsLoad($this, $properties)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    /** ToDo какие-то дубли */
    public function validateCustom(array $data, array $rules): bool
    {
        $messageBag = CustomValidatorFactory::make($data, $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }

    public function toCamelCaseKeys(array $data): array
    {
        $camelCaseAttributes = [];
        foreach ($data as $attribute => $value) {
            $camelCaseAttributes[Str::camel($attribute)] = is_array($value)
                ? $this->toCamelCaseKeys($value)
                : $value;
        }
        return $camelCaseAttributes;
    }

    public function setProperties(array $data): void
    {
        parent::setProperties($this->getOnlyTransferProperties($data)->toArray());
    }

    public function setProperty(string $property, $value): void
    {
        try {
            if ($this->collector()->has($property)) {
                $value = $this->collector()->collect($property, $value);
            }
            parent::setProperty($property, $value);
        } catch (Throwable $e) {
            $this->errors()->set($property, $e->getMessage());
        }
    }

    public function className(): string
    {
        return get_class_name($this);
    }

    public function getClassName(): string
    {
        return $this->className();
    }

    public function getOnlyTransferProperties(array $data): CollectionInterface
    {
        return Collection::make($data)->only($this->getProperties()->toArray());
    }

    public function clone(): TransferInterface
    {
        return clone $this;
    }

    /**
     * TODO что-то не нравится что метод errors без путей
     * @return ErrorCollectionInterface
     */
    public function getErrors(): ErrorCollectionInterface
    {
        if ($this->hasPath()) {
            return $this->errors()->withPath($this->getPath());
        }
        return $this->errors();
    }
}
