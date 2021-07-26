<?php

namespace Rbz\Forms\Errors\Collection;

use Illuminate\Contracts\Support\Arrayable;

class ErrorItem implements Arrayable
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
            'attribute' => $this->getAttribute(),
            'messages' => $this->getMessages(),
        ];
    }

    public function equalTo(string $attribute): bool
    {
        return $this->attribute == $attribute;
    }

    public function addMessage(string $message): void
    {
        $this->addMessages((array) $message);
    }

    public function addMessages(array $messages): void
    {
        $this->messages = array_unique(array_merge($this->messages, $messages));
    }
}
