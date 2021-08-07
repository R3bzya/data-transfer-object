<?php

namespace Rbz\Forms;

use Illuminate\Support\Facades\Validator;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Collections\Validation\ValidationAttributeCollection;
use Rbz\Forms\ValueObjects\SafeAttributes;

abstract class FormValidator extends FormErrors
{
    private ValidationAttributeCollection $validationAttributes;

    abstract public function rules(): array;

    public function validationAttributes(): ValidationAttributeCollection
    {
        if (! isset($this->validationAttributes)) {
            $this->validationAttributes = new ValidationAttributeCollection();
        }
        return $this->validationAttributes;
    }

    public function getValidationAttributes(): ValidationAttributeCollection
    {
        return $this->validationAttributes();
    }

    public function validate(array $attributes = []): bool
    {
        $this->validationAttributes()->load($attributes);

        $validate = $this->validateAttributes($this->getSafeAttributes()->getAttributes());
        if ($validate && $rules = $this->rules()) {
            return $this->validateRules(
                $this->getSafeAttributes()->toArray(),
                $this->getSafeAttributes()->filter($rules, true)
            );
        }
        return $validate;
    }

    public function validateRules(array $attributes, array $rules): bool
    {
        $messageBag = Validator::make($attributes, $rules)->getMessageBag();
        $this->errors()->load($messageBag->toArray());
        return $messageBag->isEmpty();
    }

    public function validateAttributes(array $attributes): bool
    {
        $validate = true;
        foreach ($attributes as $attribute) {
            $validate = $this->validateAttribute($attribute) && $validate;
        }
        return $validate;
    }

    public function validateAttribute(string $attribute): bool
    {
        if (! $this->hasAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::undefined($attribute));
            return false;
        }

        if (! $this->isSetAttribute($attribute) && ! $this->isNullAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::required($attribute));
            return false;
        }

        return true;
    }

    public function getSafeAttributes(): SafeAttributes
    {
        return new SafeAttributes($this, $this->validationAttributes());
    }
}
