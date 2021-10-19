<?php

namespace Rbz\Data\Interfaces\Validators;

use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface RuleInterface
{
    public function handle(TransferInterface $transfer, string $property): bool;
    public function getErrors(): CollectionInterface;
}
