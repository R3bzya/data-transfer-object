<?php

namespace Rbz\Forms\Validator\Rules;

use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;

class HasRule extends Rule
{
    public function check(FormInterface $form, string $attribute): bool
    {
        if (! $form->hasAttribute($attribute)) {
            $this->error()->add($attribute, ErrorMessage::undefined($attribute));
            return false;
        }
        return true;
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return $this->error();
    }
}
