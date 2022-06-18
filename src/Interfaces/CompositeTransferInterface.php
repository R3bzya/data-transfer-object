<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Components\Collector\CollectorProviderInterface;

interface CompositeTransferInterface extends TransferInterface,
    CollectorProviderInterface //ContainerProviderInterface
{

}
