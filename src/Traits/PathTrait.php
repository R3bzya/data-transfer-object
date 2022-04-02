<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

trait PathTrait
{
    private PathInterface $_path;

    public function setPath(PathInterface $path)
    {
        $this->_path = $path;
        return $this;
    }

    public function path(): PathInterface
    {
        if (! isset($this->_path)) {
            $this->_path = Path::make();
        }
        return $this->_path;
    }

    public function getPath(): PathInterface
    {
        return $this->path();
    }
}
