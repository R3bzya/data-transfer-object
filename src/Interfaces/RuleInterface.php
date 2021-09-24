<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface RuleInterface
{
    public function handle(TransferInterface $transfer, string $attribute): bool;
    public function getErrors(): ErrorCollectionInterface;
}
