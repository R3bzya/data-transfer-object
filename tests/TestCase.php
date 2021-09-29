<?php

namespace Rbz\DataTransfer\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Rbz\DataTransfer\Tests\Unit\Transfers\DefaultTransfer;

class TestCase extends BaseTestCase
{
    public function transfer(): DefaultTransfer
    {
        return new DefaultTransfer();
    }
}
