<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Rbz\Data\Interfaces\Components\Cloneable;

interface PathProviderInterface extends Cloneable
{
    /**
     * @param PathInterface $path
     * @return static
     */
    public function setPath(PathInterface $path);

    public function getPath(): PathInterface;
    public function hasPath(): bool;

    /**
     * @param PathInterface $path
     * @return static
     */
    public function withPath(PathInterface $path);
}
