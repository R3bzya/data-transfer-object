<?php

namespace Rbz\DataTransfer\Validators;

use DomainException;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Validators\Rules\RuleInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsNullRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;

class Validator implements ValidatorInterface
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
    private array $initialized;

    public function __construct(TransferInterface $transfer, array $rules)
    {
        $this->transfer = $transfer;
        $this->initialized = $this->initializeRules($rules);
    }

    public function validate(): bool
    {
        foreach ($this->initialized as $property => $propertyRules) {
            foreach ($propertyRules as $propertyRule) {
                $this->handle($this->make($propertyRule), $this->transfer, $property);
            }
        }
        return $this->errors()->isEmpty();
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

    public function initializeRules(array $rules): array
    {
        $initialized = [];
        foreach ($rules as $property => $propertyRules) {
            if ($this->transfer->hasProperty($property)) {
                foreach ($propertyRules as $propertyRule) {
                    $initialized[$property][] = $this->getRuleClass($this->getRuleKey($propertyRule));
                }
                continue;
            }
            if (is_array($propertyRules)) {
                $this->errors()->add($property, 'Unknown property: ' . get_class($this->transfer) . '::' . $property);
                continue;
            }
            foreach ($this->transfer->getProperties() as $transferProperty) {
                $initialized[$transferProperty][] = $this->getRuleClass($this->getRuleKey($propertyRules));
            }
        }
        return $initialized;
    }

    public function getRuleClass(string $key): string
    {
        if ($class = $this->rules[$key] ?? null) {
            return $class;
        }
        throw new DomainException("Rule `$key` not found");
    }

    public function getRuleKey(string $rule): string
    {
        if (($key = $this->findByRuleClass($rule)) || ($key = $this->findInRuleAssociations($rule))) {
            return $key;
        }
        throw new DomainException("Rule `$key` not found");
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
}
