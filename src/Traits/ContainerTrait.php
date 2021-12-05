<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Container;
use Rbz\Data\Interfaces\Components\ContainerInterface;

trait ContainerTrait
{
    private ContainerInterface $_container;

    abstract public function internalTransfers(): array;

    public function container(): ContainerInterface
    {
        if (! isset($this->_container)) {
            $this->_container = new Container();
        }
        return $this->_container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container();
    }

    public function __get($name)
    {
        if ($this->container()->has($name)) {
            return $this->container()->get($name);
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (Collection::make($this->internalTransfers())->in($name, true)) {
            $this->container()->add($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }
}
