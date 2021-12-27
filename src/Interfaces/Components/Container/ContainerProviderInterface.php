<?php

namespace Rbz\Data\Interfaces\Components\Container;

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
