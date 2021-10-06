<?php

namespace Rbz\DataTransfer\Traits;

use Rbz\DataTransfer\Validators\Filter;
use Rbz\DataTransfer\Interfaces\Validators\FilterInterface;

trait FilterTrait
{
    private FilterInterface $filter;

    public function filter(): FilterInterface
    {
        if (! isset($this->filter)) {
            $this->filter = new Filter();
        }
        return $this->filter;
    }

    public function setFilter(FilterInterface $filter): void
    {
        $this->filter = $filter;
    }

    public function getFilter(): FilterInterface
    {
        return $this->filter();
    }

    public function makeFilter(array $properties, array $rules = []): FilterInterface
    {
        return $this->filter()->setProperties($properties)->setRules($rules);
    }
}
