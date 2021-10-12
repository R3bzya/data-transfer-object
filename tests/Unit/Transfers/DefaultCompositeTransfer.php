<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

class DefaultCompositeTransfer extends CompositeTransfer
{
    public DefaultTransfer $defaultTransfer;

    public function __construct()
    {
        $this->defaultTransfer = new DefaultTransfer();
    }
}
