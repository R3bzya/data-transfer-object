<?php

namespace Rbz\Forms\Composites;

use Rbz\Forms\Collections\Validation\ValidationAttributeCollection;
use Rbz\Forms\Form;

abstract class CompositeFormValidator extends CompositeFormErrors
{
    abstract public function getForm(string $attribute): Form;
    abstract public function getAdditionalForms(): array;

    public function validate(array $attributes = []): bool
    {
        $validate = parent::validate($this->getFormAttributes($attributes));
        foreach ($this->getAdditionalForms() as $form) {
            $validate = $this->getForm($form)->validate($this->getAttributesForForm($attributes, $form)) && $validate;
        }
        return $validate;
    }

    public function getValidationAttributes(): ValidationAttributeCollection
    {
        $collection = parent::getValidationAttributes();
        foreach ($this->getAdditionalForms() as $form) {
            $collection = $collection->with($this->getForm($form)->getValidationAttributes());
        }
        return $collection;
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

    private function getAttributesForForm(array $attributes, string $form): array
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
        return explode('.', $rule);
    }

    public function implodeValidationAttributes(array $rule): string
    {
        return implode('.', $rule);
    }

    private function isFormAttributes(array $rules, string $form): bool
    {
        return count($rules) > 1 && $rules[0] == $form;
    }

    private function unsetFormAttribute(array &$exploded, string $form): void
    {
        unset($exploded[array_search($form, $exploded)]);
    }
}
