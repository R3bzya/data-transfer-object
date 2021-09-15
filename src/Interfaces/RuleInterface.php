<?php

namespace Rbz\Forms\Interfaces;

use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;

interface RuleInterface
{
    public function handle(FormInterface $form, string $attribute): bool;
    public function getErrors(): ErrorCollectionInterface;
}
