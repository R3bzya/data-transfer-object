<?php

namespace Rbz\DataTransfer\Validator\Rules;

use Rbz\DataTransfer\Errors\ErrorMessage;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class IsNullRule extends Rule
{
    public function handle(TransferInterface $transfer, string $attribute): bool
    {
        if (! $transfer->isNullAttribute($attribute)) {
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
