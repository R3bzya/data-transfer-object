<?php

namespace Rbz\DataTransfer\Interfaces;

interface RulesInterface
{
    public static function make(TransferInterface $transfer, array $rules, array $attributes): ValidatorInterface;
    public static function load(TransferInterface $transfer, array $attributes): ValidatorInterface;
}
