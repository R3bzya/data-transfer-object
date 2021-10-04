<?php

namespace Rbz\DataTransfer\Validators\Rules\Property;

use Rbz\DataTransfer\Errors\ErrorList;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class HasRule extends Rule
{
    public function handle(TransferInterface $transfer, string $property): bool
    {
        if (! $transfer->hasProperty($property)) {
            $this->errors()->add($property, ErrorList::undefined($property));
        }
        return $this->errors()->isEmpty();
    }
}
