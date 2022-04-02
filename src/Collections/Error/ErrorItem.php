<?php

namespace Rbz\Data\Collections\Error;

use Rbz\Data\Components\Path;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Traits\PathTrait;

class ErrorItem implements ErrorItemInterface
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
     * @return ErrorItemInterface
     * @throws PathException
     */
    public static function make(string $property, array $messages, PathInterface $path = null): ErrorItemInterface
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
        foreach ($this->messages as $message) {
            return $message;
        }
        return null;
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
