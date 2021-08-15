<?php

namespace Rbz\Forms;

use DomainException;
use Rbz\Forms\Collections\Error\ErrorCollection;
use Rbz\Forms\Interfaces\FormInterface;
use Rbz\Forms\Validator\Validator;

abstract class CompositeForm extends Form
{
    public function load(array $data): bool
    {
        $success = parent::load($data);
        foreach ($this->getAdditionalForms() as $form) {
            $success = $this->getForm($form)->load($data[$form] ?? $data) && $success;
        }
        return $success;
    }

    public function validate(array $attributes = []): bool
    {
        $validate = parent::validate($this->getFormAttributes($attributes));
        foreach ($this->getAdditionalForms() as $form) {
            $validate = $this->getForm($form)->validate($this->getAttributesForForm($attributes, $form)) && $validate;
        }
        return $validate;
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
            return $this->getAttribute($attribute) instanceof FormInterface;
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
    public function getForm(string $attribute): FormInterface
    {
        if (! $this->isFormAttribute($attribute)) {
            throw new DomainException("Attribute `$attribute` is not implementing FormInterface");
        }
        return $this->getAttribute($attribute);
    }

    public function getErrors(): ErrorCollection
    {
        $collection = parent::getErrors();
        foreach ($this->getAdditionalForms() as $form) {
            $collection = $collection->with($this->getForm($form)->getErrors());
        }
        return $collection;
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }

    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->getErrors()->getFirstMessage($attribute);
    }

    public function getErrorCount(): int
    {
        $count = parent::getErrorCount();
        foreach ($this->getAdditionalForms() as $form) {
            $count += $this->getForm($form)->getErrors()->count();
        }
        return $count;
    }

    public function getFormAttributes(array $attributes): array
    {
        $rules = [];
        foreach ($attributes as $attribute) {
            if (count($this->explodeValidationAttributes($attribute)) == 1) {
                $rules[] = $attribute;
            }
        }
        return $rules;
    }

    public function getAttributesForForm(array $attributes, string $form): array
    {
        $rules = [];
        foreach ($attributes as $attribute) {
            $exploded = $this->explodeValidationAttributes($attribute);
            if ($this->isFormAttributes($exploded, $form)) {
                $this->unsetFormAttribute($exploded, $form);
                $rules[] = $this->implodeValidationAttributes($exploded);
            }
        }
        return $rules;
    }

    public function explodeValidationAttributes(string $rule): array
    {
        return explode(Validator::SEPARATION_SYMBOL, $rule);
    }

    public function implodeValidationAttributes(array $rule): string
    {
        return implode(Validator::SEPARATION_SYMBOL, $rule);
    }

    public function isFormAttributes(array $rules, string $form): bool
    {
        return count($rules) > 1 && $rules[0] == $form;
    }

    public function unsetFormAttribute(array &$exploded, string $form): void
    {
        unset($exploded[array_search($form, $exploded)]);
    }
}
