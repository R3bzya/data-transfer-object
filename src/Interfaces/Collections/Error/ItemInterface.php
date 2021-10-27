<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

interface ItemInterface extends Arrayable, Countable, PathProviderInterface
{
    public static function make(string $property, array $messages, PathInterface $path): ItemInterface;
    public function getProperty(): string;
    public function getMessages(): array;
    public function addMessage(string $message): void;
    public function addMessages(array $messages): void;
    public function getMessage(): string;
}
