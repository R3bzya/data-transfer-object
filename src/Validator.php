<?php

namespace Rbz\Forms;

use Illuminate\Support\Facades\Validator as CustomValidator;
use Rbz\Forms\Collections\Error\ErrorCollection;
use Rbz\Forms\Collections\Accessible\AccessibleCollection;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;
use Rbz\Forms\Interfaces\ValidatorInterface;

class Validator implements ValidatorInterface
{
    const EXCLUSION_SYMBOL = '!';
    const SEPARATION_SYMBOL = '.';

    private FormInterface $form;
    private AccessibleCollectionInterface $accessible;
    private ErrorCollectionInterface $errors;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
        $this->accessible = new AccessibleCollection();
        $this->errors = new ErrorCollection();
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

    public function validateAttributes(): bool
    {
        $validate = true;
        foreach ($this->form->getAttributes() as $attribute) {
            $validate = $this->validateAttribute($attribute) && $validate;
        }
        return $validate;
    }

    public function validateAttribute(string $attribute): bool
    {
        if (! $this->form->hasAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::undefined($attribute));
            return false;
        }

        if (! $this->form->isSetAttribute($attribute) && ! $this->form->isNullAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::required($attribute));
            return false;
        }

        return true;
    }

    public function customValidate(array $rules): bool
    {
        $messageBag = CustomValidator::make($this->form->toArray(), $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }

    public function loadAccessible(array $data): void
    {
        foreach ($data as $attribute) {
            if ($this->hasAttribute($attribute)) {
                $this->accessible()->add($attribute);
            }
        }
    }

    public function hasAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->form->getAttributes())
            || in_array(self::EXCLUSION_SYMBOL.$attribute, $this->form->getAttributes());
    }
}
