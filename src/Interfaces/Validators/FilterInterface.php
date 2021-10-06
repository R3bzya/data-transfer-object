<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Transfer;

interface FilterInterface
{
    public function filterTransfer(Transfer $transfer): array;
    public function filterArray(array $array): array;
    public function filterArrayKeys(array $data): array;
    public function all(): array;
    public function getRules();
    public function filter(): array;
}
