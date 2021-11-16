<?php

namespace Rbz\Data;

use Illuminate\Support\Str;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Traits\CollectorTrait;
use Rbz\Data\Traits\ErrorCollectionTrait;
use Rbz\Data\Traits\PathTrait;
use Rbz\Data\Validators\Validator;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait,
        CollectorTrait,
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
        $data = Collection::make($data)->toArray();
        $this->setProperties($data);
        $loadedProperties = $this->getTransferData($data)->keys()->toArray() ?: $this->getProperties()->toArray();
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

    public function getTransferData(array $data): CollectionInterface
    {
        return Collection::make($data)->only($this->getProperties()->toArray());
    }

    public function clone(): TransferInterface
    {
        return clone $this;
    }

    public function setPath(PathInterface $path)
    {
        $this->_path = $path;
        $this->errors()->setPath($this->path());
        return $this;
    }

    public function withPath(PathInterface $path)
    {
        if ($this->hasPath()) {
            $clone = $this->clone()->setPath($this->path()->with($path));
        } else {
            $clone = $this->clone()->setPath($path);
        }

        return $clone->setErrors($clone->errors()->withPath($clone->getPath()));
    }
}
