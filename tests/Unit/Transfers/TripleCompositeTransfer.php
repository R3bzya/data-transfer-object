<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

/**
 * @property DefaultCompositeTransfer $composite
 * @property DefaultTransfer $transfer
 */
class TripleCompositeTransfer extends CompositeTransfer
{
    public int $c_one_i;

    public function internalTransfers(): array
    {
        return [
            'composite' => DefaultCompositeTransfer::class,
            'transfer' => DefaultTransfer::class,
        ];
    }
}
