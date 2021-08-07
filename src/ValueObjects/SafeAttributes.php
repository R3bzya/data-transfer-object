<?php

namespace Rbz\Forms\ValueObjects;

use Rbz\Forms\Collections\Validation\ValidationAttributeCollection;
use Rbz\Forms\FormValidator;

class SafeAttributes
{
    private FormValidator $form;
    private ValidationAttributeCollection $collection;

    public function __construct(FormValidator $form, ValidationAttributeCollection $collection)
    {
        $this->form = $form;
        $this->collection = $collection;
    }

    public function toArray(): array
    {
        $attributes = [];
        foreach ($this->getAttributes() as $attribute) {
            $attributes[$attribute] = $this->form->attributeAsArray($attribute);
        }
        return $attributes;
    }

    public function getAttributes(): array
    {
        return $this->collection->filter($this->form->getAttributes());
    }

    public function filter(array $attributes, bool $keys): array
    {
        return $this->collection->filter($attributes, $keys);
    }
}
