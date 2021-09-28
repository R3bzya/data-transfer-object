<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Errors\ErrorMessage;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class IsNullRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->isNullAttribute($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::notSet($attribute));
            return false;
        }
        return true;
    }
}
