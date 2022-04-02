<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Rbz\Data\Interfaces\Support\Cloneable;

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
}
