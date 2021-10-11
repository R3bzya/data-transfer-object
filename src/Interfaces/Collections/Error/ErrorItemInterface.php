<?php

namespace Rbz\DataTransfer\Interfaces\Collections\Error;

use Illuminate\Contracts\Support\Arrayable;

interface ErrorItemInterface extends Arrayable
{
    public static function make(string $property, array $messages): ErrorItemInterface;
    public function getProperty(): string;
    public function getMessages(): array;
    public function addMessage(string $message): void;
    public function addMessages(array $messages): void;
    public function getMessage(): string;
    public function count(): int;
}
