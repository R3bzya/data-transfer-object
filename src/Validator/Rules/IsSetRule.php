<?php

namespace Rbz\Forms\Validator\Rules;

use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;

class IsSetRule extends Rule
{
    public function handle(FormInterface $form, string $attribute): bool
    {
        if (! $form->isSetAttribute($attribute)) {
            $this->error()->add($attribute, ErrorMessage::notSet($attribute));
            return false;
        }
        return true;
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return $this->error();
    }
}
