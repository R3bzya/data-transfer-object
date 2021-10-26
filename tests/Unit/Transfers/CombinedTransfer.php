<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Transfer;

class CombinedTransfer extends Transfer
{
    /** @var DefaultTransfer[]  */
    public array $defaultTransfers = [];

    /** @var Collection[] */
    public array $collections = [];

    protected array $combinedProperties = [
        'defaultTransfers' => DefaultTransfer::class,
        'collections' => Collection::class,
    ];
}
