<?php

namespace Rbz\Forms;

use DomainException;
use Rbz\Forms\Errors\Collection\ErrorCollection;
use Throwable;

abstract class CompositeForm extends Form
{
    abstract public function additionalForms(): array;

    public function load(array $data): bool
    {
        $success = true;

        foreach ($this->getAdditionalForms() as $form) {
            $success = $this->getForm($form)->load($data) && $success;
        }

        return parent::load($data) && $success;
    }

    public function setAttributes(array $data): bool
    {
        foreach ($data as $attribute => $value) {
            if ($this->hasAttribute($attribute) && ! $this->isAdditionalForm($attribute)) {
                try {
                    $this->setAttribute($attribute, $value);
                } catch (Throwable $e) {
                    return false;
                }
            }
        }

        return true;
    }

    public function validate(): bool
    {
        $validate = true;

        foreach ($this->getAdditionalForms() as $form) {
            $validate = $this->getForm($form)->validate() && $validate;
        }

        return parent::validate() && $validate;
    }

    public function getAdditionalForms(): array
    {
        if (! empty($this->additionalForms())) {
            return $this->additionalForms();
        }

        $additionalForms = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($this->isFormAttribute($attribute)) {
                $additionalForms[] = $this->getForm($attribute)->getFormName();
            }
        }

        return $additionalForms;
    }

    public function isFormAttribute($attribute): bool
    {
        if ($this->isSetAttribute($attribute)) {
            return $this->getAttribute($attribute) instanceof Form;
        }

        return false;
    }

    public function getErrors(): ErrorCollection
    {
        $collection = parent::getErrors();
        foreach ($this->getAdditionalForms() as $form) {
            $collection = $collection->with($this->getForm($form)->getErrors());
        }
        return $collection;
    }

    public function isAdditionalForm(string $form): bool
    {
        return in_array($form, $this->getAdditionalForms());
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }

    public function getForm(string $attribute): Form
    {
        if (! $this->isFormAttribute($attribute)) {
            throw new DomainException("Attribute `$attribute` is not a form");
        }

        return $this->getAttribute($attribute);
    }
}
