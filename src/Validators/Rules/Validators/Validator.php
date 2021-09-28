<?php

namespace Rbz\DataTransfer\Validators\Rules\Validators;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\RuleInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\ValidatorInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;

/**
 * ToDo [Class::class] or ['property' => Class::class] and ['ruleTitle'] or ['property' => ruleTitle]
 */
class Validator implements ValidatorInterface
{
    use ErrorCollectionTrait;

    private TransferInterface $transfer;
    private array $attributes;
    /** @var RuleInterface[] */
    private array $rules;
    /** @var RuleInterface[] */
    private array $fails = [];

    public function __construct(TransferInterface $transfer, array $rules, array $attributes)
    {
        $this->transfer = $transfer;
        /** ToDo */
        $this->rules = [HasRule::class, IsSetRule::class];
        $this->attributes = $attributes;
    }

    /** ToDo */
    public function validate(): bool
    {
        foreach ($this->rules as $rule) {
            foreach ($this->attributes() as $attribute) {
                $ruleObj = new $rule;
                if (! $ruleObj->handle($this->transfer, $attribute)) {
                    $this->fails[$attribute][] = $ruleObj;
                }
            }
        }
        return $this->errors()->isEmpty();
    }

    private function attributes(): array
    {
        return $this->attributes ?: $this->transfer->getAttributes();
    }

    /** ToDo */
    public function getErrors(): ErrorCollectionInterface
    {
        $this->validate();

        foreach ($this->fails as $fails) {
            foreach ($fails as $rule) {
                $this->errors()->merge($rule->getErrors());
            }
        }

        return $this->errors();
    }
}
