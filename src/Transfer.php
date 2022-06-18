<?php

namespace Rbz\Data;

use Rbz\Data\Exceptions\TransferException;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Traits\ErrorBagTrait;
use Rbz\Data\Support\Transfer\Rules;
use Rbz\Data\Traits\EventsTrait;
use Rbz\Data\Validation\Validator;
use ReflectionClass;
use ReflectionException;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorBagTrait,
        EventsTrait;
    
    protected bool $isLoad = false;
    
    protected bool $isValidate = false;

    /**
     * @param mixed $data
     * @param array $constructArgs
     * @return static
     * @throws ReflectionException|TransferException
     */
    public static function make($data = [], array $constructArgs = []): TransferInterface
    {
        $reflection = new ReflectionClass(static::class);
        if (! $reflection->isInstantiable()) {
            throw new TransferException('Class ' . static::class . 'is not instantiable');
        }
        /** @var TransferInterface $transfer */
        $transfer = $reflection->newInstanceArgs($constructArgs);
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
        $this->beforeLoadEvents();
        $this->errors()->clear();
        $collection = Arr::collect($data)->only($this->getProperties()->toArray());
        $this->setProperties($collection->toArray());
        $this->setIsLoad($collection->isNotEmpty() && $this->errors()->isEmpty());
        $this->setIsValidate($this->isLoad() && $collection->isEmpty()); // TODO
        $this->afterLoadEvents();
        return $this->isLoad();
    }

    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        $this->beforeValidateEvents();
        
        $validator = Validator::make(
            $this->toSafeCollection()->toArray(),
            (new Rules($this->rules()))->only(Rules::toValidation($this->getProperties(), $properties))
        );
        $validator->validate();
        
        $this->setIsValidate($clearErrors
            ? $this->errors()->replace($validator->getErrors())->isEmpty()
            : $this->errors()->merge($validator->getErrors())->isEmpty()
        );
        
        $this->afterValidateEvents();
        return $this->isValidate();
    }

    public function setProperty(string $property, $value): void
    {
        try {
            parent::setProperty($property, $value);
        } catch (Throwable $e) {
            $this->errors()->set($property, $e->getMessage());
        }
    }

    public function clone()
    {
        return clone $this;
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function toJson(): string
    {
        return $this->toCollection()->toJson();
    }

    public function toCollection(): CollectionInterface
    {
        return $this->getProperties()
            ->mapWithKeys(fn(string $property) => [$property => $this->getProperty($property)]);
    }

    public function toSafeCollection(): CollectionInterface
    {
        return $this->getProperties()
            ->filter(fn(string $property) => $this->isSetProperty($property))
            ->mapWithKeys(fn(string $property) => [$property => $this->getProperty($property)]);
    }
    
    /**
     * @param bool $isLoad
     * @return static
     */
    public function setIsLoad(bool $isLoad)
    {
        $this->isLoad = $isLoad;
        return $this;
    }
    
    /**
     * @param bool $isValidate
     * @return static
     */
    public function setIsValidate(bool $isValidate)
    {
        $this->isValidate = $isValidate;
        return $this;
    }
    
    public function isLoad(): bool
    {
        return $this->isLoad;
    }
    
    public function isValidate(): bool
    {
        return $this->isValidate;
    }
}
