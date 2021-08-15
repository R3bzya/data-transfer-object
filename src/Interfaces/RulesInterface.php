<?php

namespace Rbz\Forms\Interfaces;

interface RulesInterface
{
    public static function make(string $rule): RuleInterface;
    public function check(FormInterface $form, array $rules, array $attributes = []): bool;
}
