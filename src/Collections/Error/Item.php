<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Interfaces\Collections\Error\ItemInterface;
use Rbz\Data\Interfaces\Components\PathInterface;

class Item implements ItemInterface
{
    private string $property;
    private array $messages;
    private PathInterface $path;

    /**
     * @param string $property
     * @param array $messages
     * @param PathInterface $path
     */
    public function __construct(string $property, array $messages, PathInterface $path)
    {
        $this->property = $property;
        $this->messages = $messages;
        $this->path = $path;
    }

    /**
     * @param string $property
     * @param array $messages
     * @param PathInterface $path
     * @return ItemInterface
     */
    public static function make(string $property, array $messages, PathInterface $path): ItemInterface
    {
        return new self($property, $messages, $path);
    }

    public function messages(): array
    {
        return $this->messages;
    }

    public function getMessages(): array
    {
        return $this->messages();
    }

    public function property(): string
    {
        return $this->property;
    }

    public function getProperty(): string
    {
        return $this->property();
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function getPath(): PathInterface
    {
        return $this->path();
    }

    public function toArray(): array
    {
        return [
            'property' => $this->property(),
            'messages' => $this->messages(),
            'path' => $this->path()->asString(),
        ];
    }

    public function addMessage(string $message): void
    {
        $this->addMessages((array) $message);
    }

    public function addMessages(array $messages): void
    {
        $this->messages = array_unique(array_merge($this->messages(), $messages));
    }

    public function getMessage(): string
    {
        return $this->messages[0] ?? '';
    }

    public function count(): int
    {
        return count($this->messages());
    }

    public function withPath(PathInterface $path): ItemInterface
    {
        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }
}
