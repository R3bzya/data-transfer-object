<?php

namespace Rbz\DataTransfer\Interfaces;

interface RulesInterface
{
    public static function make(string $rule): RuleInterface;
    public function check(TransferInterface $transfer, array $rules, array $attributes = []): bool;
}
