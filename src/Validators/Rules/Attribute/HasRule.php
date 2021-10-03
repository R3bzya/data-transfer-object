<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Errors\ErrorList;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class HasRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->hasProperty($attribute)) {
            $this->errors()->add($attribute, ErrorList::undefined($attribute));
            return false;
        }
        return true;
    }
}
