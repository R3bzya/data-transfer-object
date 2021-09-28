<?php

namespace Rbz\DataTransfer\Validators\Rules\Attribute;

use Rbz\DataTransfer\Interfaces\RuleInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;

abstract class Rule implements RuleInterface
{
    use ErrorCollectionTrait;
}
