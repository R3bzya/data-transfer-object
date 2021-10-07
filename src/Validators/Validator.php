<?php

namespace Rbz\DataTransfer\Validators;

use DomainException;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Validators\RuleInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;
use Rbz\DataTransfer\Traits\ErrorCollectionTrait;
use Rbz\DataTransfer\Validators\Rules\Property\HasRule;
use Rbz\DataTransfer\Validators\Rules\Property\IsNullRule;
use Rbz\DataTransfer\Validators\Rules\Property\IsSetRule;

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

    public static function addRulesToProperties(array $properties, array $rules): array
    {
        $prepared = [];
        foreach ($properties as $property) {
            $prepared[$property] = $rules;
        }
        return $prepared;
    }

    public static function makeIsLoad(TransferInterface $transfer, array $properties): self
    {
        return self::make($transfer, self::addRulesToProperties($properties, ['has', 'isset']));
    }

    public static function make(TransferInterface $transfer, array $rules): self
    {
        return new self($transfer, $rules);
    }

    public static function makeHas(TransferInterface $transfer, array $properties): self
    {
        return self::make($transfer, self::addRulesToProperties($properties, ['has']));
    }

    public function validate(): bool
    {
        foreach ($this->initialized as $property => $propertyRules) {
            foreach ($propertyRules as $propertyRule) {
                $this->handle($this->makeRule($propertyRule), $this->transfer, $property);
            }
        }
        return $this->errors()->isEmpty();
    }

    public function makeRule(string $ruleClass): RuleInterface
    {
        if (! class_exists($ruleClass)) {
            throw new DomainException("Class $ruleClass not found");
        }
        return new $ruleClass;
    }

    public function handle(RuleInterface $rule, TransferInterface $transfer, string $property): void
    {
        if (! $rule->handle($transfer, $property)) {
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
            if ($this->shouldBeExcluded($property)) {
                continue;
            }
            $initialized = array_merge_recursive($initialized,
                $this->preparePropertyRules(
                    $this->getPropertiesAsArray($property),
                    $this->getRulesAsArray($propertyRules)
                )
            );
        }
        return $initialized;
    }

    public function preparePropertyRules(array $properties, array $rules): array
    {
        $prepared = [];
        foreach ($properties as $property) {
            foreach ($rules as $rule) {
                $prepared[$property][] = $this->getRuleClassByKey($this->getRuleKey($rule));
            }
        }
        return $prepared;
    }

    public function getPropertiesAsArray(string $property): array
    {
        return is_numeric($property) ? $this->transfer->getProperties() : (array) $property;
    }

    /**
     * @param array|string $rules
     * @return array
     */
    public function getRulesAsArray($rules): array
    {
        return is_array($rules) ? $rules : (array) $rules;
    }

    public function getRuleClassByKey(string $key): string
    {
        if ($class = $this->rules[$key] ?? null) {
            return $class;
        }
        throw new DomainException("Rule class not found for `$key`");
    }

    public function getRuleKey(string $rule): string
    {
        if (($key = $this->findByRuleClass($rule)) || ($key = $this->findInRuleAssociations($rule))) {
            return $key;
        }
        throw new DomainException("Rule `$rule` not found");
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
     * @param mixed $property
     * @return bool
     */
    public function shouldBeExcluded($property): bool
    {
        return is_string($property) && str_starts_with($property, Filter::SYMBOL_EXCLUDE);
    }
}
