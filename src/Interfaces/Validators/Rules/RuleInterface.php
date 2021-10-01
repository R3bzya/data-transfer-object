<?php

namespace Rbz\DataTransfer\Interfaces\Validators\Rules;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

interface RuleInterface
{
    public function handle(TransferInterface $transfer, string $attribute): bool;
    public function getErrors(): ErrorCollectionInterface;
}
