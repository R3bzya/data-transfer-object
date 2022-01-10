<?php

namespace Rbz\Data;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Exceptions\TransferException;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Validation\Validator as AbstractValidator;
use Rbz\Data\Validation\Helpers\RuleHelper;
use ReflectionClass;
use ReflectionException;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait;

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
        $this->errors()->clear();
        $collection = Collection::make($data)->only($this->getProperties()->toArray());
        $this->setProperties($collection->toArray());
        return $collection->isNotEmpty() && $this->errors()->isEmpty();
    }

    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        $errors = AbstractValidator::make(
            $this->toSafeCollection()->toArray(),
            (new RuleHelper($this->rules()))->run(RuleHelper::toValidation($this->getProperties(), $properties))
        )->getErrors();

        return $clearErrors
            ? $this->errors()->replace($errors)->isEmpty()
            : $this->errors()->merge($errors)->isEmpty();
    }

    public function setProperty(string $property, $value): void
    {
        try {
            parent::setProperty($property, $value);
        } catch (Throwable $e) {
            $this->errors()->set($property, $e->getMessage());
        }
    }

    public function clone(): TransferInterface
    {
        return clone $this;
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function toCollection(): CollectionInterface
    {
        return $this->getProperties()
            ->flip()
            ->map(fn($value, string $property) => $this->getProperty($property));
    }

    public function toSafeCollection(): CollectionInterface
    {
        return $this->getProperties()
            ->flip()
            ->filter(fn($value, string $property) => $this->isSetProperty($property))
            ->map(fn($value, string $property) => $this->getProperty($property));
    }
}
