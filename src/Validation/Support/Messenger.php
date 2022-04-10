<?php

namespace Rbz\Data\Validation\Support;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Messenger
{
    private array $templates = [
        'integer' => 'The :property must be an integer.',
        'numeric' => 'The :property must be a numeric.',
        'string' => 'The :property must be a string.',
        'array' => 'The :property must be an array.',
        'present' => 'The :property must be a present.',
        'required' => 'The :property is required',
    ];

    public function getMessage(string $property, string $rule): string
    {
        return Str::replace(':property', $property, $this->getTemplate($rule));
    }

    private function getTemplate(string $rule): string
    {
        $templates = $this->getTemplates();
        if (! Arr::has($templates, $rule)) {
            return "The :property not did not pass the $rule";
        }
        return $templates[$rule];
    }

    private function getTemplates(): array
    {
        return $this->templates;
    }
}
