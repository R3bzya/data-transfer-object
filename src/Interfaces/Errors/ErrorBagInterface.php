<?php

namespace Rbz\Data\Interfaces\Errors;

use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Interfaces\Support\Arrayable;
use Rbz\Data\Interfaces\Support\Cloneable;
use Rbz\Data\Interfaces\Support\Collectable;

interface ErrorBagInterface extends Cloneable, Arrayable, Collectable
{
    public function get(string $key);

    public function has(string $key): bool;

    public function set(string $key, $value = null);

    /**
     * Add the item to the error collection.
     *
     * @param ErrorInterface $item
     * @return static
     */
    public function addItem(ErrorInterface $item);

    /**
     * Get the first error message from the error collection.
     *
     * @param string|null $property
     * @return string|null
     */
    public function getFirstMessage(string $property = null): ?string;

    /**
     * Add the path to the path beginning of each item.
     *
     * @param PathInterface $path
     * @return ErrorBagInterface
     */
    public function prependPathsWith(PathInterface $path): ErrorBagInterface;

    /**
     * Count messages of error items.
     *
     * @return int
     */
    public function countMessages(): int;

    public function count(): int;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function clear(): void;

    public function replace(ErrorBagInterface $bag);

    public function merge(ErrorBagInterface $bag);

    public function with(ErrorBagInterface $bag);

    public function first(): ?ErrorInterface;

    public function getMessages(): array;
}
