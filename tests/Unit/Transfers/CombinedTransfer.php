<?php

namespace Rbz\DataTransfer\Tests\Unit\Transfers;

use Rbz\DataTransfer\Transfer;

class CombinedTransfer extends Transfer
{
    /** @var DefaultTransfer[]  */
    public array $combined = [];

    protected array $combinations = [
        'combined' => 123131313,
    ];
}
