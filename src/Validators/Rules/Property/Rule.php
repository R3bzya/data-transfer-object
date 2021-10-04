<?php

namespace Rbz\DataTransfer\Validators\Rules\Property;

use Rbz\DataTransfer\Interfaces\Validators\RuleInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;

abstract class Rule implements RuleInterface
{
    use ErrorCollectionTrait;
}
