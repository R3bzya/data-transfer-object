<?php

namespace Rbz\DataTransfer\Validator\Rules;

use Rbz\DataTransfer\Errors\ErrorMessage;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class HasRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->hasAttribute($attribute)) {
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
