<?php

namespace Rbz\DataTransfer\Validators;

use Illuminate\Support\Facades\Validator as CustomValidator;
use Rbz\DataTransfer\Collections\Accessible\AccessibleCollection;
use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\FacadeInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Factory as ValidatorFactory;

class Facade implements FacadeInterface
{
    use ErrorCollectionTrait;

    const SYMBOL_EXCLUSION = '!';
    const SYMBOL_SEPARATION = '.';

    private TransferInterface $transfer;
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

    public function validate(array $attributes = [], array $customRules = []): bool
    {
        $this->accessible()->load($attributes);
        $validate = $this->isLoadTransfer($this->transfer,
            $this->accessible()->filter($this->transfer->getProperties())
        );
        if ($validate && ! empty($customRules)) {
            return $this->validateCustom(
                $this->accessible()->filter($this->transfer->toArray()),
                $this->accessible()->filterKeys($customRules)
            );
        }
        return $validate;
    }

    public function validateAttributes(TransferInterface $transfer, array $rules, array $attributes): bool
    {
        $errors = ValidatorFactory::make($transfer, $rules, $attributes)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    public function isLoadTransfer(TransferInterface $transfer, array $attributes): bool
    {
        $errors = ValidatorFactory::makeIsLoad($transfer, $attributes)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }

    public function validateCustom(array $data, array $rules): bool
    {
        $messageBag = CustomValidator::make($data, $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }

    public function isSetProperties(TransferInterface $transfer, array $attributes): bool
    {
        $errors = ValidatorFactory::makeIsSet($transfer, $attributes)->getErrors();
        $this->errors()->merge($errors);
        return $errors->isEmpty();
    }
}
