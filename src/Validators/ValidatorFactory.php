<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;

class ValidatorFactory
{
    public function makeIsLoad(TransferInterface $transfer, array $properties): ValidatorInterface
    {
        return $this->make($transfer, $this->addRulesToProperties(
            $properties ?: $transfer->getProperties(), [HasRule::class, IsSetRule::class]
        ));
    }

    public function make(TransferInterface $transfer, array $rules): ValidatorInterface
    {
        return new Validator($transfer, $rules);
    }

    public function addRulesToProperties(array $properties, array $rules): array
    {
        $prepared = [];
        foreach ($properties as $property) {
            $prepared[$property] = $rules;
        }
        return $prepared;
    }
}
