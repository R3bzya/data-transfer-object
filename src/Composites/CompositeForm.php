<?php

namespace Rbz\Forms\Composites;

use DomainException;
use Rbz\Forms\Form;

abstract class CompositeForm extends CompositeFormValidator
{
    public function load(array $data): bool
    {
        $success = parent::load($data);
        foreach ($this->getAdditionalForms() as $form) {
            $success = $this->getForm($form)->load($data[$form] ?? $data) && $success;
        }
        return $success;
    }

    public function setAttributes(array $data): bool
    {
        $success = parent::setAttributes($this->getThisFormFields($data));
        foreach ($this->getDataFormsForThisForm($data) as $form => $value) {
            $success = $this->getForm($form)->load($value) && $success;
        }
        return $success;
    }

    private function getThisFormFields(array $data): array
    {
        return array_filter_keys($data, function ($attribute) {
            return ! $this->isAdditionalForm($attribute);
        });
    }

    private function getDataFormsForThisForm(array $data): array
    {
        return array_filter($data, function ($value, $attribute) {
            return $this->isAdditionalForm($attribute) && is_array($value);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function getAdditionalForms(): array
    {
        $additionalForms = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($this->isFormAttribute($attribute)) {
                $additionalForms[] = $attribute;
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

    public function isAdditionalForm(string $form): bool
    {
        return in_array($form, $this->getAdditionalForms());
    }

    /**
     * @throws DomainException
     */
    public function getForm(string $attribute): Form
    {
        if (! $this->isFormAttribute($attribute)) {
            throw new DomainException("Attribute `$attribute` is not a form");
        }
        return $this->getAttribute($attribute);
    }
}
