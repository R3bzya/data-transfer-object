<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

/**
 * @property DefaultCompositeTransfer $composite
 */
class TripleCompositeTransfer extends CompositeTransfer
{
    public int $c_one_i;

    public function internalTransfers(): array
    {
        return [
            'composite' => DefaultCompositeTransfer::class,
        ];
    }
}
