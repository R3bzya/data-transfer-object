<?php

namespace Rbz\DataTransfer\Tests\Unit\Transfers;

class CustomRulesTransfer extends DefaultTransfer
{
    public function rules(): array
    {
        return [
            'a_one_s' => 'required|string|min:3'
        ];
    }
}
