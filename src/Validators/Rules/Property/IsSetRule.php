<?php

namespace Rbz\DataTransfer\Validators\Rules\Property;

use Rbz\DataTransfer\Errors\ErrorList;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class IsSetRule extends Rule
{
    public function handle(TransferInterface $transfer, string $property): bool
    {
        if (! $transfer->isSetProperty($property)) {
            $this->errors()->add($property, ErrorList::notSet($property));
        }
        return $this->errors()->isEmpty();
    }
}
