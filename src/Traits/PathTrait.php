<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

trait PathTrait
{
    private PathInterface $path;

    public function setPath(PathInterface $path)
    {
        $this->path = $path;
        return $this;
    }

    public function path(): PathInterface
    {
        if (! isset($this->path)) {
            $this->path = Path::make();
        }
        return $this->path;
    }

    public function getPath(): PathInterface
    {
        return $this->path();
    }
}
