<?php

namespace Rbz\Data\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\Components\CombinatorInterface;

interface TransferInterface extends PropertiesInterface
{
    public static function make($data = []): TransferInterface;

    /**
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;
    public function validate(array $properties = []): bool;
    public function getErrors(): CollectionInterface;
    public function getCombinator(): CombinatorInterface;
    public function getClassName(): string;
    public function hasErrors(): bool;
}
