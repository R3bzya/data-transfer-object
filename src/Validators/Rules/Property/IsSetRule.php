<?php

namespace Rbz\Data\Validators\Rules\Property;

use Rbz\Data\Errors\ErrorList;
use Rbz\Data\Interfaces\TransferInterface;

class IsSetRule extends Rule
{
    public function handle(TransferInterface $transfer, string $property): bool
    {
        if (! $transfer->isSetProperty($property)) {
            $this->errors()->set($property, ErrorList::notSet($property));
        }
        return $this->errors()->isEmpty();
    }
}
