<?php

namespace Rbz\Forms;

use Illuminate\Support\Str;
use Rbz\Forms\Errors\ErrorMessage;
use Rbz\Forms\Interfaces\FromInterface;

abstract class Form extends FormValidator
    implements FromInterface
{
    public function load(array $data): bool
    {
        if (empty($data)) {
            $this->errors()->add($this->getFormName(), ErrorMessage::notLoad($this->getFormName()));
            return false;
        }

        return $this->setAttributes($data);
    }

    protected function toCamelCase(string $value): string
    {
        return Str::camel($value);
    }

    public function toCamelCaseKeys(array $data): array
    {
        $camelCaseAttributes = [];
        foreach ($data as $attribute => $value) {
            $camelCaseAttributes[$this->toCamelCase($attribute)] = is_array($value)
                ? $this->toCamelCaseKeys($value)
                : $value;
        }
        return $camelCaseAttributes;
    }

    public function getFormName(): string
    {
        return get_class_name($this);
    }
}
