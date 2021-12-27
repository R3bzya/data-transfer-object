<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Container;
use Rbz\Data\Exceptions\PropertyException;
use Rbz\Data\Interfaces\Components\Container\ContainerInterface;
use Rbz\Data\Interfaces\TransferInterface;

trait ContainerTrait
{
    private ContainerInterface $_container;

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
            if (! $value instanceof TransferInterface) {
                throw new PropertyException('Property ' . $name . ' must implement interface ' . TransferInterface::class . ', ' . gettype($value) . ' given');
            }
            $this->container()->add($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->_container = $container;
        return $this;
    }

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

    public function internalTransfers(): array
    {
        return [];
    }
}
