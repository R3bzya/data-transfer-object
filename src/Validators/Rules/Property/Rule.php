<?php

namespace Rbz\Data\Validators\Rules\Property;

use Rbz\Data\Interfaces\Validators\RuleInterface;
use Rbz\Data\Traits\ErrorCollectionTrait;

abstract class Rule implements RuleInterface
{
    use ErrorCollectionTrait;
}
