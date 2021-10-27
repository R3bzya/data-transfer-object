<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Traits\PathTrait;

class ErrorItem implements ErrorItemInterface
{
    use PathTrait;

    private string $property;
    private array $messages;

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
     * @return ErrorItemInterface
     */
    public static function make(string $property, array $messages, PathInterface $path): ErrorItemInterface
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

    public function toArray(): array
    {
        return [
            'property' => $this->property(),
            'messages' => $this->messages(),
            'path' => $this->path()->get(),
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

    public function clone()
    {
        return clone $this;
    }
}
