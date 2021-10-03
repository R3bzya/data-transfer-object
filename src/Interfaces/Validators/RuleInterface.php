<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

interface RuleInterface
{
    public function handle(TransferInterface $transfer, string $attribute): bool;
    public function getErrors(): ErrorCollectionInterface;
}
