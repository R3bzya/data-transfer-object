<?php

namespace Rbz\DataTransfer\Tests\Unit\Transfers;

use Rbz\DataTransfer\CompositeTransfer;

class DefaultCompositeTransfer extends CompositeTransfer
{
    public DefaultTransfer $defaultTransfer;

    public function __construct()
    {
        $this->defaultTransfer = new DefaultTransfer();
    }
}
