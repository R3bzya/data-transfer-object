<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

class DefaultCompositeTransfer extends CompositeTransfer
{
    public string $b_one_s;

    public DefaultTransfer $defaultTransfer;

    public function __construct()
    {
        $this->defaultTransfer = new DefaultTransfer();
    }
}
