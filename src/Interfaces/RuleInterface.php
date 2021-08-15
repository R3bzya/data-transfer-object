<?php

namespace Rbz\Forms\Interfaces;

use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;

interface RuleInterface
{
    public function check(FormInterface $form, string $attribute): bool;
    public function getErrors(): ErrorCollectionInterface;
}
