<?php

namespace Rbz\Forms;

use Rbz\Forms\Errors\Collection\ErrorCollection;
use Throwable;

abstract class CompositeForm extends Form
{
    abstract public function additionalForms(): array;

    public function load(array $data): bool
    {
        $success = true;

        foreach ($this->getAdditionalForms() as $form) {
            $success = $this->$form->load($data) && $success;
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
            if ($this->isFormAttribute($form)) {
                $validate = $this->$form->validate() && $validate;
            }
        }

        return parent::validate() && $validate;
    }

    public function getAdditionalForms(): array
    {
        if (! empty($this->additionalForms())) {
            return $this->additionalForms();
        }

        $additionalForm = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($this->isFormAttribute($attribute)) {
                /** @var Form $form */
                $form = $this->getAttribute($attribute);
                $additionalForm[] = $form->getFormName();
            }
        }

        return $additionalForm;
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
            if ($this->isFormAttribute($form)) {
                $collection = $collection->with($this->$form->getErrors());
            }
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
}
