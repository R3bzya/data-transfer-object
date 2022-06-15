<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Container\TransferManager;
use Rbz\Data\Interfaces\Components\Container\ContainerInterface;

trait ContainerTrait
{
    private ContainerInterface $_container;

    public function setContainer(ContainerInterface $container)
    {
        $this->_container = $container;
        return $this;
    }

    public function container(): ContainerInterface
    {
        if (! isset($this->_container)) {
            $this->_container = new TransferManager();
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
