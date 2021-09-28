<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Errors\ErrorMessage;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class HasRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->hasAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::undefined($attribute));
            return false;
        }
        return true;
    }
}
