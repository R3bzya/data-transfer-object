<?php

namespace Rbz\Data\Validation\Validators;

use Illuminate\Support\Facades\Validator;
use Rbz\Data\Collections\Error\ErrorCollection;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Validation\Validator as AbstractValidator;

class Laravel extends AbstractValidator
{
    private array $data;
    private array $rules;

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate(): bool
    {
        return $this->getErrors()->isEmpty();
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return ErrorCollection::make()->load(Validator::make($this->data, $this->rules)->getMessageBag()->toArray());
    }

    protected static function defaultRules(): array
    {
        return ['present'];
    }
}
