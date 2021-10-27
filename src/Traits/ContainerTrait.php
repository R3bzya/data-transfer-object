<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Path;
use Rbz\Data\Components\Container;
use Rbz\Data\Interfaces\Components\ContainerInterface;

trait ContainerTrait
{
    private ContainerInterface $transfersHandler;

    public function container(): ContainerInterface
    {
        if (! isset($this->transfersHandler)) {
            $this->transfersHandler = new Container();
        }
        return $this->transfersHandler;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container();
    }

    public function __get($name)
    {
        if ($this->container()->has($name)) {
            if ($this->hasPath()) {
                return $this->container()->get($name)->withPath($this->path()->with(Path::make($name)));
            } else {
                return $this->container()->get($name)->withPath(Path::make($name));
            }
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($this->container()->has($name)) {
            $this->container()->set($name, $value);
        }
        if (key_exists($name, $this->internalTransfers()) && ! $this->container()->has($name)) {
            $this->container()->add($name, $value);
        }
        if (! $this->container()->has($name)) {
            parent::__set($name, $value);
        }
    }

    public function initContainer(): void
    {
        if (! $this->container()->isLoad()) {
            $this->container()->load($this->internalTransfers());
        }
    }
}
