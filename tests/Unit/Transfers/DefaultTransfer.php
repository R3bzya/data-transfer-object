<?php

namespace Rbz\DataTransfer\Tests\Unit\Transfers;

use Rbz\DataTransfer\Transfer;

class DefaultTransfer extends Transfer
{
    public string $a_one_s; // string
    public int $a_two_i; // integer
    public array $a_three_a; // array

    public function rules(): array
    {
        return [];
    }
}
