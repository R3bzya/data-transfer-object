<?php

namespace Rbz\DataTransfer\Tests;

use Rbz\DataTransfer\Collections\Error\ErrorCollection;
use Rbz\DataTransfer\Collections\Error\ValueObjects\Path;
use Rbz\DataTransfer\Tests\Unit\Transfers\CombinedTransfer;
use Rbz\DataTransfer\Tests\Unit\Transfers\CustomRulesTransfer;
use Rbz\DataTransfer\Tests\Unit\Transfers\DefaultCompositeTransfer;
use Rbz\DataTransfer\Tests\Unit\Transfers\DefaultTransfer;
use Tests\TestCase;

class BaseCase extends TestCase
{
    public function compositeTransfer(): DefaultCompositeTransfer
    {
        return new DefaultCompositeTransfer();
    }

    public function transfer(): DefaultTransfer
    {
        return new DefaultTransfer();
    }

    public function transferWithCustomRules(): CustomRulesTransfer
    {
        return new CustomRulesTransfer();
    }

    public function combinedTransfer(): CombinedTransfer
    {
        return new CombinedTransfer();
    }

    public function errorCollection(): ErrorCollection
    {
        return new ErrorCollection(Path::make('BaseCase'));
    }
}
