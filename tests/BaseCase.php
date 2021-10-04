<?php

namespace Rbz\DataTransfer\Tests;

use Rbz\DataTransfer\Tests\Unit\Transfers\CustomRulesTransfer;
use Rbz\DataTransfer\Tests\Unit\Transfers\DefaultTransfer;
use Tests\TestCase;

class BaseCase extends TestCase
{
    public function transfer(): DefaultTransfer
    {
        return new DefaultTransfer();
    }

    public function transferWithCustomRules(): CustomRulesTransfer
    {
        return new CustomRulesTransfer();
    }
}
