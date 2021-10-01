<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Errors\ErrorMessage;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class IsSetRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->isSetProperty($attribute)) {
            $this->errors()->add($attribute, ErrorMessage::notSet($attribute));
            return false;
        }
        return true;
    }
}
