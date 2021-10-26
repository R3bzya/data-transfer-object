<?php

namespace Rbz\Data\Tests;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Collections\Error\Collection as ErrorCollection;
use Rbz\Data\Components\Path;
use Rbz\Data\Tests\Unit\Transfers\CollectableTransfer;
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

    public function collectableTransfer(): CollectableTransfer
    {
        return new CollectableTransfer();
    }

    public function errorCollection(): ErrorCollection
    {
        return (new ErrorCollection([]))->withPath(Path::make('BaseCase'));
    }

    public function collection($data = []): Collection
    {
        return Collection::make($data);
    }
}
