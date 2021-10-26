<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Combinator;
use Rbz\Data\Interfaces\Components\CombinatorInterface;

trait CombinatorTrait
{
    protected array $combinedProperties = [];

    private CombinatorInterface $combinator;

    public function setCombinator(CombinatorInterface $combinator): void
    {
        $this->combinator = $combinator;
    }

    public function combinator(): CombinatorInterface
    {
        if (! isset($this->combinator)) {
            $this->combinator = new Combinator($this->combinedProperties);
        }
        return $this->combinator;
    }

    public function getCombinator(): CombinatorInterface
    {
        return $this->combinator();
    }
}
