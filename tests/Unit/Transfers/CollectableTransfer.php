<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Transfer;

class CollectableTransfer extends Transfer
{
    /** @var DefaultTransfer[]  */
    public array $defaultTransfers = [];

    /** @var Collection[] */
    public array $collections = [];

    public function collectable(): array
    {
        return [
            'defaultTransfers' => DefaultTransfer::class,
            'collections' => Collection::class,
        ];
    }
}
