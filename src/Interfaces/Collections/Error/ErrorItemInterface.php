<?php

namespace Rbz\DataTransfer\Interfaces\Collections\Error;

use Illuminate\Contracts\Support\Arrayable;

interface ErrorItemInterface extends Arrayable
{
    public function getProperty(): string;
    public function getMessages(): array;
    public function addMessage(string $message): void;
    public function addMessages(array $messages): void;
    public function getMessage(): string;
    public function count(): int;
}
