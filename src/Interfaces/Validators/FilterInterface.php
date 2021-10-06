<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Transfer;

interface FilterInterface
{
    public function setProperties(array $properties): FilterInterface;
    public function setRules(array $properties): FilterInterface;
    public function getProperties(): array;
    public function transferData(Transfer $transfer): array;
    public function array(array $data): array;
}
