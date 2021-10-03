<?php

namespace Rbz\DataTransfer;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\ValidatorFactory;
use Throwable;

abstract class Transfer extends Properties
    implements TransferInterface
{
    use ErrorCollectionTrait;

    abstract public function rules(): array;

    public function load(array $data): bool
    {
        if (! empty($data)) {
            $this->setProperties($data);
        }

        $factory = new ValidatorFactory();
        $errors = $factory->makeIsLoad($this, array_keys($data))->getErrors();

        if ($errors->isNotEmpty()) {
            $this->errors()->merge($errors);
            return false;
        }

        return true;
    }

    public function validate(array $attributes = []): bool
    {
        if ($this->errors()->isNotEmpty()) {
            return false;
        }

        $factory = new ValidatorFactory();
        $errors = $factory->makeIsLoad($this, $attributes)->getErrors();

        if ($errors->isNotEmpty()) {
            $this->errors()->merge($errors);
            return false;
        }

        if ($this->rules()) {
            $messageBag = Validator::make($this->getProperties(), $this->rules())->getMessageBag();

            if ($messageBag->isNotEmpty()) {
                $this->errors()->load($messageBag->toArray());
            }

            return $messageBag->isEmpty();
        }

        return true;
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
