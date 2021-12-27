<?php

namespace Rbz\Data\Interfaces\Collections\Error;

use Rbz\Data\Interfaces\Collections\CollectionInterface as BaseCollectionInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

interface ErrorCollectionInterface extends BaseCollectionInterface
{
    /**
     * Add the item to the error collection.
     *
     * @param ErrorItemInterface $item
     * @return static
     */
    public function addItem(ErrorItemInterface $item);

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
     * @return static
     */
    public function withPathAtTheBeginning(PathInterface $path);
}
