<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Components\PathInterface;

interface ItemInterface extends Arrayable
{
    public static function make(string $property, array $messages, PathInterface $path): ItemInterface;
    public function getProperty(): string;
    public function getMessages(): array;
    public function getPath(): PathInterface;
    public function addMessage(string $message): void;
    public function addMessages(array $messages): void;
    public function getMessage(): string;
    public function count(): int;
    public function getFullPath(): string;
    public function withPath(PathInterface $path): ItemInterface;
}
