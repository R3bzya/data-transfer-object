<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Rbz\Data\Transfer;

class DefaultTransfer extends Transfer
{
    public string $a_one_s;
    public int $a_two_i;
    public array $a_three_a;
    //public ?string $a_four_ns = null; // TODO

    public function rules(): array
    {
        return [
            'a_one_s' => ['string'],
            'a_two_i' => ['integer'],
            'a_three_a' => ['array']
        ];
    }
}
