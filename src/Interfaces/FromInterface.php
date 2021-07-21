<?php

namespace Rbz\Forms\Interfaces;

interface FromInterface
{
    public function load(array $data): bool;

    public function validate(): bool;
}
