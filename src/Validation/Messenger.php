<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Exceptions\MessageException;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Messenger
{
    private array $templates = [
        'integer' => 'Property :property will be an integer.',
        'numeric' => 'Property :property will be a numeric.',
        'string' => 'Property :property will be a string.',
        'array' => 'Property :property will be an array.',
        'present' => 'Property :property will be a present.',
    ];

    public function getMessage(string $property, string $rule): string
    {
        return Str::replace(':property', $property, $this->getTemplate($rule));
    }

    private function getTemplate(string $rule): string
    {
        $templates = $this->getTemplates();
        if (! Arr::has($templates, $rule)) {
            throw new MessageException("Template for {$rule} not found");
        }
        return $templates[$rule];
    }

    private function getTemplates(): array
    {
        return $this->templates;
    }
}
