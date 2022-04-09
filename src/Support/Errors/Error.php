<?php

namespace Rbz\Data\Support\Errors;

use Rbz\Data\Components\Path;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Errors\ErrorInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Traits\PathTrait;

class Error implements ErrorInterface
{
    use PathTrait;

    private string $property;
    private array $messages;

    /**
     * @param string $property
     * @param array $messages
     * @param PathInterface|null $path
     * @throws PathException
     */
    public function __construct(string $property, array $messages, PathInterface $path = null)
    {
        $this->property = $property;
        $this->messages = $messages;
        $this->_path = $path ?: Path::make($property);
    }

    /**
     * @param string $property
     * @param array $messages
     * @param PathInterface|null $path
     * @return ErrorInterface
     * @throws PathException
     */
    public static function make(string $property, array $messages, PathInterface $path = null): ErrorInterface
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
        $this->messages = Arr::unique((Arr::merge($this->messages(), $messages)));
    }

    public function getMessage(): ?string
    {
        return Arr::first($this->messages());
    }

    public function count(): int
    {
        return Arr::count($this->messages());
    }

    public function clone()
    {
        return clone $this;
    }
}
