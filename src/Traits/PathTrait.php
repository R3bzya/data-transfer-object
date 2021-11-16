<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Interfaces\Components\Path\PathInterface;

trait PathTrait
{
    private PathInterface $_path;

    public function setPath(PathInterface $_path)
    {
        $this->_path = $_path;
        return $this;
    }

    public function path(): PathInterface
    {
        return $this->_path;
    }

    public function getPath(): PathInterface
    {
        return $this->path();
    }

    public function hasPath(): bool
    {
        return isset($this->_path);
    }

    public function withPath(PathInterface $path)
    {
        if ($this->hasPath()) {
            return $this->clone()->setPath($this->path()->with($path));
        }
        return $this->clone()->setPath($path);
    }
}
