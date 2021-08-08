<?php

namespace Rbz\Forms\Collections\Error;

use Rbz\Forms\Interfaces\Collections\Items\ItemInterface;

class ErrorItem implements ItemInterface
{
    private string $attribute;
    private array $messages;

    /**
     * @param string $attribute
     * @param array $messages
     */
    public function __construct(string $attribute, array $messages)
    {
        $this->attribute = $attribute;
        $this->messages = $messages;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function toArray(): array
    {
        return [
            'attribute' => $this->attribute,
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
}
