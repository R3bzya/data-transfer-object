<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Container\TransferManager;

trait ContainerTrait
{
    private TransferManager $transferManager;

    public function transferManager(): TransferManager
    {
        if (! isset($this->transferManager)) {
            $this->transferManager = new TransferManager();
        }
        return $this->transferManager;
    }

    public function internalTransfers(): array
    {
        return [];
    }
}
