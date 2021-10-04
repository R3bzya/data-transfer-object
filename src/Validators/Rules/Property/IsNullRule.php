<?php

namespace Rbz\DataTransfer\Validators\Rules\Property;

use Rbz\DataTransfer\Errors\ErrorList;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class IsNullRule extends Rule
{
    public function handle(TransferInterface $transfer, string $property): bool
    {
        if (! $transfer->isNullProperty($property)) {
            $this->errors()->add($property, ErrorList::notSet($property));
        }
        return $this->errors()->isEmpty();
    }
}
