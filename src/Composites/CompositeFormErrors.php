<?php

namespace Rbz\Forms\Composites;

use Rbz\Forms\Collections\Error\ErrorCollection;
use Rbz\Forms\Form;

abstract class CompositeFormErrors extends Form
{
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
        return $this->getErrors()->count();
    }
}
