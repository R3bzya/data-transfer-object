<?php

namespace Rbz\Data\Validators\Rules\Property;

use Rbz\Data\Errors\ErrorList;
use Rbz\Data\Interfaces\TransferInterface;

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
