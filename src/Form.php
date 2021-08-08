<?php

namespace Rbz\Forms;

use Illuminate\Support\Str;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;
use Rbz\Forms\Interfaces\ValidatorInterface;

abstract class Form extends Attributes
    implements FormInterface
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
        if (empty($data)) {
            $this->errors()->add($this->getFormName(), ErrorMessage::notLoad($this->getFormName()));
            return false;
        }

        return $this->setAttributes($data);
    }

    public function validate(array $attributes = []): bool
    {
        $this->validator()->loadAccessible($attributes);

        $validate = $this->validator()->validateAttributes();
        if ($validate && $rules = $this->rules()) {
            return $this->validator()->customValidate($rules);
        }
        return $validate;
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

    public function getFormName(): string
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

    public function setErrors(ErrorCollectionInterface $collection): void
    {
        $this->validator()->setErrors($collection);
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }

    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->errors()->getFirstMessage($attribute);
    }

    public function getErrorCount(): int
    {
        return $this->errors()->count();
    }

    public function setAttribute(string $attribute, $value): bool
    {
        if (! parent::setAttribute($attribute, $value)) {
            $this->errors()->add($attribute, ErrorMessage::notSet($attribute));
            return false;
        }
        return true;
    }
}
