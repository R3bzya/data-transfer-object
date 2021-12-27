<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Rbz\Data\Interfaces\Cloneable;

interface PathProviderInterface extends Cloneable
{
    /**
     * Set the path to the instance.
     *
     * @param PathInterface $path
     * @return static
     */
    public function setPath(PathInterface $path);

    /**
     * Get the path from the instance.
     *
     * @return PathInterface
     */
    public function getPath(): PathInterface;

    /**
     * Determine if the path exists in the instance.
     *
     * @return bool
     */
    public function hasPath(): bool;
}
