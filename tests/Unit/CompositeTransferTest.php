<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Tests\Unit\Transfers\TripleCompositeTransfer;

class CompositeTransferTest extends BaseCase
{
    public function testComposite()
    {
        $transfer = new TripleCompositeTransfer();

        //dd($transfer->composite->defaultTransfer->getErrors());

        $transfer->validate();


        //dd($transfer->composite->getFilter()->getPath(), $transfer->composite->defaultTransfer->getFilter()->getPath());

        $this->assertTrue(false);
    }
}
