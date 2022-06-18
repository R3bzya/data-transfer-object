<?php

namespace Rbz\Data\Interfaces\Container;

use Rbz\Data\Interfaces\Container\ContainerInterface;

interface ContainerProviderInterface
{
    /**
     * Set the container to the instance.
     *
     * @param ContainerInterface $container
     * @return static
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Get the container.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;
}
