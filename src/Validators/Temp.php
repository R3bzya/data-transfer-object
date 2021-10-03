<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\TransferInterface;
use Illuminate\Support\Facades\Validator as DataValidator;
use Illuminate\Contracts\Validation\Validator as DataValidatorInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;

class Temp
{
    private TransferInterface $transfer;

    private ValidatorInterface $transferValidator;
    private DataValidatorInterface $dataValidator;

    public function __construct(TransferInterface $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * валидация определенных полей правилами
     * каждое правило, кладется к атрибутам
     */
    public function validate_0(array $properties, array $rules): bool
    {
        $prepared = [];
        foreach ($properties as $property) {
            $prepared[$property] = $rules;
        }
        return $this->transferValidate($prepared);
    }

    public function transferValidate(array $rules): bool
    {
        return $this->makeTransferValidator($this->transfer, $rules)->validate();
    }

    public function dataValidate(array $rules): bool
    {
        return ! $this->makeDataValidator($this->transfer->toArray(), $rules)->fails();
    }

    public function validate_4(array $rules, array $custom)
    {

    }

    public function makeTransferValidator(TransferInterface $transfer, array $rules): ValidatorInterface
    {
        if (! isset($this->transferValidator)) {
            $this->transferValidator = new Validator($transfer, $rules);
        }
        return $this->transferValidator;
    }

    public static function makeDataValidator(array $data, array $rules): DataValidatorInterface
    {
//        if (! isset($this->dataValidator)) {
//            $this->dataValidator = DataValidator::make($data, $rules);
//        }
        return DataValidator::make($data, $rules);
    }

    public function isLoad(array $properties): bool
    {
        $prepared = [];
        foreach ($properties as $property) {
            $prepared[$property] = [HasRule::class, IsSetRule::class];
        }
        return $this->transferValidate($prepared);
    }
}
