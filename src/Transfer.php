<?php

namespace Rbz\DataTransfer;

use Illuminate\Support\Str;
use Rbz\DataTransfer\Errors\ErrorMessage;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\TransferValidatorInterface;
use Rbz\DataTransfer\Validators\Validator;

abstract class Transfer extends Attributes
    implements TransferInterface
{
    private TransferValidatorInterface $validator;

    abstract public function rules(): array;

    public function validator(): TransferValidatorInterface
    {
        if (! isset($this->validator)) {
            $this->validator = new Validator($this);
        }
        return $this->validator;
    }

    public function getValidator(): TransferValidatorInterface
    {
        return $this->validator();
    }

    public function setValidator(TransferValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function load(array $data): bool
    {
        if (empty($data)) {
            $this->errors()->add($this->getTransferName(), ErrorMessage::notLoad($this->getTransferName()));
            return false;
        }
        return $this->setAttributes($data);
    }

    public function validate(array $attributes = []): bool
    {
        return $this->validator()->setCustomRules($this->rules())->validate($attributes);
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

    public function setAttribute(string $attribute, $value): bool
    {
        if (! parent::setAttribute($attribute, $value)) {
            $this->errors()->add($attribute, ErrorMessage::notSet($attribute));
            return false;
        }
        return true;
    }
}
