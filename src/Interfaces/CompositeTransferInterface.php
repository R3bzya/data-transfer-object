<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Components\Collector\CollectorProviderInterface;
use Rbz\Data\Interfaces\Components\Container\ContainerProviderInterface;

interface CompositeTransferInterface extends TransferInterface,
    CollectorProviderInterface, ContainerProviderInterface
{

}
