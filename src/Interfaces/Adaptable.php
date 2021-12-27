<?php

namespace Rbz\Data\Interfaces;

interface Adaptable
{
    /**
     * Set the adapter class to the transfer.
     *
     * @param string $adapter
     * @return static
     */
    public function setAdapter(string $adapter);
}
