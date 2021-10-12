<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Combinator;
use Rbz\Data\Interfaces\Components\CombinatorInterface;

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
