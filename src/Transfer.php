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
    
    private bool $isLoaded = false;
    
    private bool $isValidated = false;
    
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
        $this->notLoaded();
        $collection = Arr::collect($data)->only($this->getProperties()->toArray());
        $this->setProperties($collection->toArray());
        if ($collection->isNotEmpty() && $this->errors()->isEmpty()) {
            $this->loaded();
        }
        $this->afterLoadEvents();
        return $this->isLoaded();
    }

    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        $this->beforeValidateEvents();
        $this->notValidated();
        
        $validator = Validator::make(
            $this->toSafeCollection()->toArray(),
            (new Rules($this->rules()))->only(Rules::toValidation($this->getProperties(), $properties))
        );
        
        if ($validator->validate()) {
            $this->validated();
        }
        
        if ($clearErrors) {
            $this->errors()->replace($validator->getErrors());
        } else {
            $this->errors()->merge($validator->getErrors());
        }
        
        $this->afterValidateEvents();
        return $this->isValidated();
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
     * Change the transfer status to loaded.
     *
     * @return static
     */
    public function loaded()
    {
        $this->setIsLoaded(true);
        $this->setIsValidated(false);
        return $this;
    }
    
    /**
     * Change the transfer status to not loaded.
     *
     * @return static
     */
    public function notLoaded()
    {
        $this->setIsLoaded(false);
        return $this;
    }
    
    /**
     * Change the transfer status to validated.
     *
     * @return static
     */
    public function validated()
    {
        $this->setIsValidated(true);
        return $this;
    }
    
    /**
     * Change the transfer status to not validated.
     *
     * @return static
     */
    public function notValidated()
    {
        $this->setIsValidated(false);
        return $this;
    }
    
    /**
     * Set the transfer loaded status.
     *
     * @param bool $isLoaded
     * @return static
     */
    public function setIsLoaded(bool $isLoaded)
    {
        $this->isLoaded = $isLoaded;
        return $this;
    }
    
    /**
     * Set the transfer loaded status.
     *
     * @param bool $isValidated
     * @return static
     */
    public function setIsValidated(bool $isValidated)
    {
        $this->isValidated = $isValidated;
        return $this;
    }
    
    /**
     * Determine if the transfer was loaded.
     *
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->isLoaded;
    }
    
    /**
     * Determine if the transfer was validated.
     *
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->isValidated;
    }
}
