<?php

namespace Rbz\Forms\ValueObjects;

use Rbz\Forms\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;

class SafeAttributes
{
    private FormInterface $form;
    private AccessibleCollectionInterface $collection;

    public function __construct(FormInterface $form, AccessibleCollectionInterface $collection)
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
        return $this->filter($this->form->getAttributes(), false);
    }

    public function filter(array $attributes, bool $keys): array
    {
        return $this->collection->filter($attributes, $keys);
    }
}
