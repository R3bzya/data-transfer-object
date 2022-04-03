<?php

namespace Rbz\Data\Interfaces\Errors;

use Countable;
use Rbz\Data\Interfaces\Support\Arrayable;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

interface ErrorInterface extends Arrayable, Countable, PathProviderInterface
{
    /**
     * Make the new error item instance.
     *
     * @param string $property
     * @param array $messages
     * @param PathInterface|null $path
     * @return ErrorInterface
     */
    public static function make(string $property, array $messages, PathInterface $path = null): ErrorInterface;

    /**
     * Get the property from the error item.
     *
     * @return string
     */
    public function getProperty(): string;

    /**
     * Get all messages from the error item.
     *
     * @return array
     */
    public function getMessages(): array;

    /**
     * Add the message to the error item.
     *
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void;

    /**
     * Add messages to the error item.
     *
     * @param array $messages
     * @return void
     */
    public function addMessages(array $messages): void;

    /**
     * Get the first message of the error item messages.
     *
     * @return string
     */
    public function getMessage(): ?string;
}
