<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Exceptions\MessageException;

class Messenger
{
    public static function getMessage(string $property, string $rule): string
    {
        return str_replace(':property', $property, self::getTemplate($rule));
    }

    private static function getTemplates(): array
    {
        return require 'templates.php';
    }

    private static function getTemplate(string $rule): string
    {
        $templates = self::getTemplates();
        if (! key_exists($rule, $templates)) {
            throw new MessageException("Template for {$rule} not found");
        }
        return $templates[$rule];
    }
}
