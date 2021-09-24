<?php

namespace Rbz\DataTransfer\Tests\Unit\Transfers;

use Rbz\DataTransfer\Transfer;

class DefaultTransfer extends Transfer
{
    public string $a_one_s;
    public int $a_two_i;
    public array $a_three_a;

    public function rules(): array
    {
        return [];
    }
}
