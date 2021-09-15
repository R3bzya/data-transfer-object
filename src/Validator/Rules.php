<?php

namespace Rbz\Forms\Validator;

use DomainException;
use Rbz\Forms\Collections\Error\ErrorCollection;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\FormInterface;
use Rbz\Forms\Validator\Rules\IsNullRule;
use Rbz\Forms\Validator\Rules\IsSetRule;
use Rbz\Forms\Interfaces\RulesInterface;
use Rbz\Forms\Interfaces\RuleInterface;
use Rbz\Forms\Validator\Rules\HasRule;

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

    public function check(FormInterface $form, array $rules, array $attributes = []): bool
    {
        $this->initialize($rules);
        foreach ($this->getInitialized() as $rule) {
            foreach ($attributes ?: $form->getAttributes() as $attribute) {
                $rule->handle($form, $attribute);
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
