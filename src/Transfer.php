<?php

namespace Rbz\Data;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Traits\CollectorTrait;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Validation\Validator as AbstractValidator;
use Rbz\Data\Validation\RuleHelper;
use ReflectionClass;
use ReflectionException;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait,
        CollectorTrait;

    /**
     * @param mixed $data
     * @param mixed ...$constructArgs
     * @return static
     * @throws ReflectionException
     */
    public static function make($data = [], ...$constructArgs): TransferInterface
    {
        $instance = new ReflectionClass(static::class);
        /** @var TransferInterface $transfer */
        $transfer = $instance->newInstanceArgs($constructArgs);
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
        $collection = $this->getTransferData($data);
        $this->setProperties($collection->toArray());
        return $this->validate($collection->keys()->toArray());
    }

    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        $errors = AbstractValidator::make(
            $this->toSafeCollection()->toArray(),
            (new RuleHelper($this->rules()))->resolve(RuleHelper::toValidation($this->getProperties(), $properties))
        )->getErrors();

        return $clearErrors
            ? $this->errors()->replace($errors)->isEmpty()
            : $this->errors()->merge($errors)->isEmpty();
    }

    public function getTransferData(array $data): CollectionInterface
    {
        return Collection::make($data)->only($this->getProperties()->toArray());
    }

    public function setProperties(array $data): void
    {
        parent::setProperties($this->getTransferData($data)->toArray());
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

    public function getShortClassName(): string
    {
        return $this->getReflectionInstance()->getShortName();
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
