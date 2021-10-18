<?php

namespace Rbz\Data\Tests;

use Rbz\Data\Collections\Error\ErrorCollection;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
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
        return (new ErrorCollection([]))->withPath(Path::make('BaseCase'));
    }

    public function collection(array $data = []): CollectionInterface
    {
        return new Collection($data);
    }
}
