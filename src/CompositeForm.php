<?php

namespace Rbz\Forms;

use DomainException;
use Rbz\Forms\Errors\Collection\ErrorCollection;

abstract class CompositeForm extends Form
{
    /**
     * @deprecated
     */
    public function additionalForms(): array
    {
        return [];
    }

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

    public function validate(): bool
    {
        $validate = parent::validate();
        foreach ($this->getAdditionalForms() as $form) {
            $validate = $this->getForm($form)->validate() && $validate;
        }
        return $validate;
    }

    public function getAdditionalForms(): array
    {
        return $this->findAdditionalForms();
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

    /**
     * @deprecated
     */
    public function findAdditionalForms(): array
    {
        $additionalForms = [];
        foreach ($this->getAttributes() as $attribute) {
            if ($this->isFormAttribute($attribute)) {
                $additionalForms[] = $attribute;
            }
        }
        return $additionalForms;
    }

    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->getErrors()->getFirstMessage($attribute);
    }

    public function getErrorCount(): int
    {
        return $this->getErrors()->count();
    }

    public function countErrorsHasNotChanged(int $count): bool
    {
        return $this->getErrors()->countErrorsHasNotChanged($count);
    }
}
