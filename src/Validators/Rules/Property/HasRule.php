<?php

namespace Rbz\Data\Validators\Rules\Property;

use Rbz\Data\Errors\ErrorList;
use Rbz\Data\Interfaces\TransferInterface;

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
