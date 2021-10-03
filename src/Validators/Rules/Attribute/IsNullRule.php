<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Errors\ErrorList;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class IsNullRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->isNullProperty($attribute)) {
            $this->errors()->add($attribute, ErrorList::notSet($attribute));
            return false;
        }
        return true;
    }
}
