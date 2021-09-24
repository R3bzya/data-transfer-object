<?php

namespace Rbz\DataTransfer\Validator;

use Illuminate\Support\Facades\Validator as CustomValidator;
use Rbz\DataTransfer\Collections\Error\ErrorCollection;
use Rbz\DataTransfer\Collections\Accessible\AccessibleCollection;
use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\RulesInterface;
use Rbz\DataTransfer\Interfaces\ValidatorInterface;

class Validator implements ValidatorInterface
{
    const EXCLUSION_SYMBOL = '!';
    const SEPARATION_SYMBOL = '.';

    private AccessibleCollectionInterface $accessible;
    private ErrorCollectionInterface $errors;
    private Rules $rules;

    public function __construct()
    {
        $this->accessible = new AccessibleCollection();
        $this->errors = new ErrorCollection();
        $this->rules = new Rules();
    }

    public function setAccessible(AccessibleCollectionInterface $collection): void
    {
        $this->accessible = $collection;
    }

    public function setErrors(ErrorCollectionInterface $collection): void
    {
        $this->errors = $collection;
    }

    public function accessible(): AccessibleCollectionInterface
    {
        return $this->accessible;
    }

    public function getAccessible(): AccessibleCollectionInterface
    {
        return $this->accessible();
    }

    public function errors(): ErrorCollectionInterface
    {
        $this->errors = $this->errors->with($this->rules()->getErrors());
        return $this->errors;
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return $this->errors();
    }

    public function rules(): RulesInterface
    {
        return $this->rules;
    }

    public function validateTransfer(TransferInterface $transfer): bool
    {
        return $this->rules()->check($transfer, Rules::$checkAvailableAttributes,
            $this->accessible()->filterTransferAttributes($transfer)
        );
    }

    public function customValidate(TransferInterface $transfer, array $rules): bool
    {
        $messageBag = CustomValidator::make($this->accessible()->filter($transfer->toArray()), $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }

    public function setAttributes(array $attributes): void
    {
        $this->accessible()->load($attributes);
    }
}
