<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\CompositeTransfer;

class TripleCompositeTransfer extends CompositeTransfer
{
    public int $c_one_i;

    public DefaultCompositeTransfer $composite;

    public function __construct()
    {
        $this->composite = new DefaultCompositeTransfer();
    }
}
