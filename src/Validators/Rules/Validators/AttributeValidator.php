<?php

namespace Rbz\DataTransfer\Validators\Rules\Validators;

use DomainException;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\RuleInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\ValidatorInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsNullRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;

class AttributeValidator implements ValidatorInterface
{
    use ErrorCollectionTrait;

    protected array $rules = [
        'isSet' => IsSetRule::class,
        'isNull' => IsNullRule::class,
        'has' => HasRule::class,
    ];

    protected array $ruleAssociations = [
        'isSet' => ['isset', 'is_set'],
        'isNull' => ['isnull', 'is_null'],
        'has' => ['has'],
    ];

    private TransferInterface $transfer;

    /** @var string[] */
    private array $attributes;
    /** @var string[] */
    private array $initialized;

    public function __construct(TransferInterface $transfer, array $rules, array $attributes)
    {
        $this->transfer = $transfer;
        $this->initializeRules($rules);
        $this->attributes = $attributes;
    }

    public function validate(): bool
    {
        foreach ($this->initialized as $rule) {
            foreach ($this->attributes() as $attribute) {
                $this->handle($this->make($rule), $this->transfer, $attribute);
            }
        }
        return $this->errors()->isEmpty();
    }

    public function attributes(): array
    {
        return $this->attributes ?: $this->transfer->getAttributes();
    }

    public function make(string $ruleClass): RuleInterface
    {
        if (! class_exists($ruleClass)) {
            throw new DomainException("Class $ruleClass not found");
        }
        return new $ruleClass;
    }

    private function handle(RuleInterface $rule, TransferInterface $transfer, string $attribute): void
    {
        if (! $rule->handle($transfer, $attribute)) {
            $this->errors()->merge($rule->getErrors());
        }
    }

    public function getErrors(): ErrorCollectionInterface
    {
        $this->validate();
        return $this->errors();
    }

    public function initializeRules(array $rules): void
    {
        foreach ($rules as $rule) {
            $this->initialized[] = $this->getRule($this->getRuleKey($rule));
        }
    }

    public function getRule(string $key): string
    {
        if ($class = $this->rules[$key] ?? null) {
            return $class;
        }
        $this->throwRuleNotFound($key);
    }

    public function getRuleKey(string $rule): string
    {
        if (($key = $this->findByRuleClass($rule)) || ($key = $this->findInRuleAssociations($rule))) {
            return $key;
        }
        $this->throwRuleNotFound($key);
    }

    public function findByRuleClass(string $rule): ?string
    {
        if (class_exists($rule) && in_array($rule, $this->rules)) {
            return array_search($rule, $this->rules);
        }
        return null;
    }

    public function findInRuleAssociations(string $rule): ?string
    {
        foreach ($this->ruleAssociations as $key => $associations) {
            if ($this->hasAssociation($rule, $associations)) {
                return $key;
            }
        }
        return null;
    }

    public function hasAssociation(string $rule, array $associations): bool
    {
        return in_array(mb_strtolower($rule), $associations);
    }

    /**
     * @throws DomainException
     */
    private function throwRuleNotFound(string $key): void
    {
        throw new DomainException("Rule `$key` not found");
    }
}
