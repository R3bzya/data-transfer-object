<?php

namespace Rbz\DataTransfer\Collections\Error;

use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorItemInterface;

class ErrorItem implements ErrorItemInterface
{
    private string $property;
    private array $messages;

    /**
     * @param string $property
     * @param array $messages
     */
    public function __construct(string $property, array $messages)
    {
        $this->property = $property;
        $this->messages = $messages;
    }

    public function messages(): array
    {
        return $this->messages;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getMessages(): array
    {
        return $this->messages();
    }

    public function toArray(): array
    {
        return [
            'property' => $this->property,
            'messages' => $this->messages,
        ];
    }

    public function addMessage(string $message): void
    {
        $this->addMessages((array) $message);
    }

    public function addMessages(array $messages): void
    {
        $this->messages = array_unique(array_merge($this->messages, $messages));
    }

    public function getMessage(): string
    {
        return $this->messages[0] ?? '';
    }

    public function count(): int
    {
        return count($this->messages);
    }
}
