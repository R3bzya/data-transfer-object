<?php

namespace Rbz\DataTransfer\Validator;

use DomainException;
use Rbz\DataTransfer\Collections\Error\ErrorCollection;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Validator\Rules\IsNullRule;
use Rbz\DataTransfer\Validator\Rules\IsSetRule;
use Rbz\DataTransfer\Interfaces\RulesInterface;
use Rbz\DataTransfer\Interfaces\RuleInterface;
use Rbz\DataTransfer\Validator\Rules\HasRule;

class Rules implements RulesInterface
{
    /** @var RuleInterface[] */
    private array $initialized = [];

    private static array $rules = [
        'isNull' => IsNullRule::class,
        'isSet' => IsSetRule::class,
        'has' => HasRule::class
    ];

    public static array $checkAvailableAttributes = [
        'has',
        'isSet'
    ];

    /**
     * @param string $rule
     * @return RuleInterface
     * @throws DomainException
     */
    public static function make(string $rule): RuleInterface
    {
        if (key_exists($rule, self::$rules)) {
            return new self::$rules[$rule];
        }
        throw new DomainException('Rule not found');
    }

    /**
     * @param array $rules
     * @return RuleInterface[]
     * @throws DomainException
     */
    public static function makeRules(array $rules): array
    {
        return array_map(function (string $rule) {
            return self::make($rule);
        }, $rules);
    }

    public function check(TransferInterface $transfer, array $rules, array $attributes = []): bool
    {
        $this->initialize($rules);
        foreach ($this->getInitialized() as $rule) {
            foreach ($attributes ?: $transfer->getAttributes() as $attribute) {
                $rule->handle($transfer, $attribute);
            }
        }
        return $this->getErrors()->isEmpty();
    }

    public function initialize(array $rules): void
    {
        $this->initialized = array_merge($this->initialized, self::makeRules($rules));
    }

    public function getInitialized(): array
    {
        return $this->initialized;
    }

    public function getErrors(): ErrorCollectionInterface
    {
        $errors = new ErrorCollection();
        foreach ($this->getInitialized() as $rule) {
            $errors = $errors->with($rule->getErrors());
        }
        return $errors;
    }
}
