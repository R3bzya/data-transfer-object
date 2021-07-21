<?php

namespace Rbz\Forms\Errors\Collection;

use Illuminate\Contracts\Support\Arrayable;

class ErrorItem implements Arrayable
{
    private string $attribute;
    private string $message;

    /**
     * @param string $attribute
     * @param string $message
     */
    public function __construct(string $attribute, string $message)
    {
        $this->attribute = $attribute;
        $this->message = $message;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function toArray(): array
    {
        return [
            'attribute' => $this->getAttribute(),
            'message' => $this->getMessage(),
        ];
    }
}
