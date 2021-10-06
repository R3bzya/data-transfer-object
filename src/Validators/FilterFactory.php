<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\Validators\FilterInterface;

class FilterFactory
{
    public function make(array $properties, array $exclude): FilterInterface
    {
        return new Filter($properties, $exclude);
    }
}
