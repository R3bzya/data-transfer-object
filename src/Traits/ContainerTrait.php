<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Path;
use Rbz\Data\Components\Container;
use Rbz\Data\Interfaces\Components\ContainerInterface;

trait ContainerTrait
{
    private ContainerInterface $container;

    public function container(): ContainerInterface
    {
        if (! isset($this->container)) {
            $this->container = new Container($this->internalTransfers());
        }
        return $this->container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container();
    }

    public function __get($name)
    {
        if ($this->container()->has($name)) {
            if ($this->hasPath()) {
                return $this->container()->get($name)->setPath($this->path()->with(Path::make($name)));
            }
            return $this->container()->get($name)->setPath(Path::make($name));
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($this->container()->has($name)) {
            $this->container()->add($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }
}