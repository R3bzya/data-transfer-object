<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Tests\Unit\Transfers\TripleCompositeTransfer;

class CompositeTransferTest extends BaseCase
{
    public function testComposite()
    {
        $transfer = new TripleCompositeTransfer();
        //$transfer->load(['a_one_s' => 'stringa?']);

        //dd($transfer->composite->defaultTransfer->a_one_s);

        $transfer->validate();

        //dd($transfer->getErrors());

        //dd($transfer->composite->getFilter()->getPath(), $transfer->composite->defaultTransfer->getFilter()->getPath());

        $this->assertTrue(false);
    }
}
