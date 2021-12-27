<?php

namespace Rbz\Data\Tests\Unit\Transfers;

use Illuminate\Support\Collection as LaravelCollection;
use Rbz\Data\Collections\Collection;
use Rbz\Data\CompositeTransfer;

class CollectableTransfer extends CompositeTransfer
{
    /** @var DefaultTransfer[]  */
    public array $defaultTransfers = [];

    /** @var Collection[] */
    public array $collections = [];

    /** @var LaravelCollection[] */
    public array $laravelCollections = [];

    public function collectables(): array
    {
        return [
            'defaultTransfers' => DefaultTransfer::class,
            'collections' => Collection::class,
            'laravelCollections' => LaravelCollection::class,
        ];
    }
}
