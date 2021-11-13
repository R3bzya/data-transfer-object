<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Components\ContainerInterface;

interface CompositeTransferInterface extends TransferInterface
{
    public function getContainer(): ContainerInterface;
}
