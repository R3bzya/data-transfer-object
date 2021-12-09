<?php

namespace Rbz\Data;

use Illuminate\Support\Str;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Traits\CollectorTrait;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Validators\Validator;
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
     * @param mixed ...$construct
     * @return static
     * @throws ReflectionException
     */
    public static function make($data = [], ...$construct): TransferInterface
    {
        $instance = new ReflectionClass(static::class);
        /** @var TransferInterface $transfer */
        $transfer = $instance->newInstanceArgs($construct);
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
        $loadedProperties = $collection->keys()->toArray() ?: $this->getProperties()->toArray();
        return $this->errors()->replace(Validator::makeIsLoad($this, $loadedProperties)->getErrors())->isEmpty();
    }

    public function validate(array $properties = []): bool
    {
        $this->errors()->replace(Validator::makeIsLoad($this, $properties)->getErrors());
        if ($this->errors()->isEmpty() && $this->rules()) {
            $this->errors()->load(Validator::makeCustom($this, $this->rules())->errors()->toArray());
        }
        return $this->errors()->isEmpty();
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
