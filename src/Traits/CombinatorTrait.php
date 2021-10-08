<?php

namespace Rbz\DataTransfer\Traits;

use Rbz\DataTransfer\Components\Combinator;
use Rbz\DataTransfer\Interfaces\Components\CombinatorInterface;

trait CombinatorTrait
{
    protected array $combinations;

    private CombinatorInterface $combinator;

    public function combinator(): CombinatorInterface
    {
        if (! isset($this->combinator)) {
            $this->combinator = new Combinator($this->combinations ?? []);
        }
        return $this->combinator;
    }

    public function getCombinator(): CombinatorInterface
    {
        return $this->combinator();
    }
}
