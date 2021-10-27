<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Filter;
use Rbz\Data\Interfaces\Components\Filter\FilterInterface;

trait FilterTrait
{
    private FilterInterface $filter;

    public function setFilter(FilterInterface $filter): void
    {
        $this->filter = $filter;
    }

    public function filter(): FilterInterface
    {
        if (! isset($this->filter)) {
            $this->filter = Filter::make($this->getProperties());
        }
        return $this->filter;
    }

    public function getFilter(): FilterInterface
    {
        return $this->filter();
    }
}
