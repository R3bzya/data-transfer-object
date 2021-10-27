<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

/**
 * @property DefaultTransfer $defaultTransfer
 */
class DefaultCompositeTransfer extends CompositeTransfer
{
    public string $b_one_s;

    public function internalTransfers(): array
    {
        return [
            'defaultTransfer' => DefaultTransfer::class
        ];
    }
}
