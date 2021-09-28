<?php

namespace Rbz\DataTransfer\Validators;

use Illuminate\Support\Facades\Validator as CustomValidator;
use Rbz\DataTransfer\Collections\Accessible\AccessibleCollection;
use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\TransferValidatorInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Rules\Rules;

class Validator implements TransferValidatorInterface
{
    use ErrorCollectionTrait;

    const SYMBOL_EXCLUSION = '!';
    const SYMBOL_SEPARATION = '.';

    private TransferInterface $transfer;
    private array $customRules = [];

    private AccessibleCollectionInterface $accessible;

    public function __construct(TransferInterface $transfer)
    {
        $this->transfer = $transfer;
    }

    public function setAccessible(AccessibleCollectionInterface $accessible): void
    {
        $this->accessible = $accessible;
    }

    public function accessible(): AccessibleCollectionInterface
    {
        if (! isset($this->accessible)) {
            $this->accessible = new AccessibleCollection();
        }
        return $this->accessible;
    }

    public function getAccessible(): AccessibleCollectionInterface
    {
        return $this->accessible();
    }

    public function validate(array $attributes = []): bool
    {
        $this->accessible()->load($attributes);
        $validate = $this->validateAttributes($this->transfer);
        if ($validate && $this->hasCustomRules()) {
            return $this->validateCustom($this->transfer, $this->customRules());
        }
        return $validate;
    }

    public function validateAttributes(TransferInterface $transfer): bool
    {
        $errors = Rules::loaded(
            $transfer,
            $this->accessible()->filter($transfer->getAttributes())
        )->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    public function validateCustom(TransferInterface $transfer, array $rules): bool
    {
        $messageBag = CustomValidator::make(
            $this->accessible()->filter($transfer->toArray()),
            $this->accessible()->filterKeys($rules)
        )->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }

    public function setCustomRules(array $rules): TransferValidatorInterface
    {
        $this->customRules = $rules;
        return $this;
    }

    public function customRules(): array
    {
        return $this->customRules;
    }

    public function hasCustomRules(): bool
    {
        return count($this->customRules()) > 0;
    }
}
