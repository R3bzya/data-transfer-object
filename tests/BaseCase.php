<?php

namespace Rbz\Data\Tests;

use Rbz\Data\Collections\Error\ErrorCollection;
use Rbz\Data\Collections\Error\ValueObjects\Path;
use Rbz\Data\Components\Data;
use Rbz\Data\Interfaces\Components\DataInterface;
use Rbz\Data\Tests\Unit\Transfers\CombinedTransfer;
use Rbz\Data\Tests\Unit\Transfers\CustomRulesTransfer;
use Rbz\Data\Tests\Unit\Transfers\DefaultCompositeTransfer;
use Rbz\Data\Tests\Unit\Transfers\DefaultTransfer;
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

    public function data(array $data = []): DataInterface
    {
        return new Data($data);
    }
}
