<?php

namespace Rbz\Forms\Validator;

use Illuminate\Support\Facades\Validator as CustomValidator;
use Rbz\Forms\Collections\Error\ErrorCollection;
use Rbz\Forms\Collections\Accessible\AccessibleCollection;
use Rbz\Forms\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;
use Rbz\Forms\Interfaces\RulesInterface;
use Rbz\Forms\Interfaces\ValidatorInterface;

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

    public function validateForm(FormInterface $form, array $attributes = []): bool
    {
        $this->accessible()->load(['in', '!out']);
        return $this->rules()->check($form, Rules::$checkAvailableAttributes,
            $this->accessible()->filterFormAttributes($form)
        );
    }

    public function customValidate(FormInterface $form, array $rules, array $attributes = []): bool
    {
        $this->accessible()->load($attributes);
        $messageBag = CustomValidator::make($this->accessible()->filter($form->toArray()), $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }
}
