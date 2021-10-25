<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\Transfer;

class CombinedTransfer extends Transfer
{
    /** @var DefaultTransfer[]  */
    public array $combined = [];

    protected array $combinations = [
        'combined' => DefaultTransfer::class,
    ];
}
