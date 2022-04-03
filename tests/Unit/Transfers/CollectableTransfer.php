<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\Support\Collection;
use Rbz\Data\CompositeTransfer;

class CollectableTransfer extends CompositeTransfer
{
    /** @var DefaultTransfer[]  */
    public array $defaultTransfers = [];

    /** @var Collection[] */
    public array $collections = [];

    public function collectables(): array
    {
        return [
            'defaultTransfers' => DefaultTransfer::class,
            'collections' => Collection::class,
        ];
    }
}
