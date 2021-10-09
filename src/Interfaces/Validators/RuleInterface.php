<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

interface RuleInterface
{
    public function handle(TransferInterface $transfer, string $property): bool;
    public function getErrors(): ErrorCollectionInterface;
}
