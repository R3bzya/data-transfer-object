<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

/**
 * @property DefaultTransfer $default
 */
class DefaultCompositeTransfer extends CompositeTransfer
{
    public string $b_one_s;

    public function __construct()
    {
        $this->default = new DefaultTransfer();
    }

    public function internalTransfers(): array
    {
        return ['default'];
    }

    public function getTestAttribute(): string
    {
        return 'Test getter';
    }
}
