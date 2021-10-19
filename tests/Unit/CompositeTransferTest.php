<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Tests\Unit\Transfers\TripleCompositeTransfer;

class CompositeTransferTest extends BaseCase
{
    public function testComposite()
    {
        $transfer = new TripleCompositeTransfer();
        $transfer->validate();

        dd($transfer->getErrors()->toArray());
    }
}
