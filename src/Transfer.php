<?php

namespace Rbz\DataTransfer;

use Illuminate\Support\Str;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\FacadeInterface as ValidatorInterface;
use Rbz\DataTransfer\Validators\Facade as Validator;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    private ValidatorInterface $validator;

    abstract public function rules(): array;

    public function validator(): ValidatorInterface
    {
        if (! isset($this->validator)) {
            $this->validator = new Validator($this);
        }
        return $this->validator;
    }

    public function getValidator(): ValidatorInterface
    {
        return $this->validator();
    }

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function load(array $data): bool
    {
        if (! empty($data)) {
            $this->setProperties($data);
        }
        return $this->validator()->isSetProperties($this, array_keys($data));
    }

    public function validate(array $attributes = []): bool
    {
        return $this->validator()->validate($attributes, $this->rules());
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

    public function errors(): ErrorCollectionInterface
    {
        return $this->validator()->getErrors();
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return $this->errors();
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }

    /** @deprecated  */
    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->errors()->getFirstMessage($attribute);
    }

    /** @deprecated  */
    public function getErrorCount(): int
    {
        return $this->errors()->count();
    }

    public function setProperty(string $property, $value): void
    {
        try {
            parent::setProperty($property, $value);
        } catch (Throwable $e) {}
    }
}
