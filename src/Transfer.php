<?php

namespace Rbz\Data;

use Illuminate\Support\Facades\Validator as CustomValidatorFactory;
use Illuminate\Support\Str;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Filter;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Traits\CombinatorTrait;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Validators\Validator;
use Throwable;

abstract class Transfer extends Properties implements TransferInterface
{
    use ErrorCollectionTrait,
        CombinatorTrait;

    protected string $adapter;

    public function __get($name)
    {
        if (isset($this->adapter) && method_exists($this->adapter, $name)) {
            return call_user_func([$this->adapter, $name], $this);
        }
        return parent::__get($name);
    }

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
        $data = Collection::make($data)->toArray();
        $this->setProperties($data);
        return $this->validateIsLoad($this->onlyTransferProperties($data)->keys()->toArray() ?: $this->getProperties());
    }

    public function validate(array $properties = []): bool
    {
        $filter = Filter::make($this->getProperties(), $properties);
        if (! $this->validateHas($filter->getRules()) || $this->errors()->isNotEmpty()) {
            return false;
        }
        $validation = $this->validateIsLoad($filter->filtered());
        if ($validation && $this->rules()) {
            return $this->validateCustom($filter->filterTransfer($this), $filter->filterArrayKeys($this->rules()));
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
        parent::setProperties($this->onlyTransferProperties($data)->toArray());
    }

    public function setProperty(string $property, $value): void
    {
        try {
            if ($this->combinator()->canCombine($property)) {
                $value = $this->combinator()->combine($property, $value);
            }
            parent::setProperty($property, $value);
        } catch (Throwable $e) {
            $this->errors()->add($property, $e->getMessage());
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

    public function onlyTransferProperties(array $data): CollectionInterface
    {
        return Collection::make($data)->only($this->getProperties());
    }
}
