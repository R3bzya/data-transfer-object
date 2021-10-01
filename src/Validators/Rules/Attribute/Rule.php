<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Interfaces\Validators\Rules\RuleInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;

abstract class Rule implements RuleInterface
{
    use ErrorCollectionTrait;
}
