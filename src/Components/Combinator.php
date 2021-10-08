<?php

namespace Rbz\DataTransfer\Components;

use DomainException;
use Rbz\DataTransfer\Interfaces\Components\CombinatorInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

class Combinator implements CombinatorInterface
{
    private array $combinations;

    public function __construct(array $combinations)
    {
        $this->combinations = $combinations;
    }

    public function combinations(): array
    {
        return $this->combinations;
    }

    public function getCombinations(): array
    {
        return $this->combinations();
    }

    public function isCombined(string $property): bool
    {
        return key_exists($property, $this->combinations());
    }

    public function getTransferClassByProperty(string $property): string
    {
        if (! $this->hasProperty($property)) {
            throw new DomainException("Property `$property` is not combined");
        }
        $class = $this->combinations()[$property];
        if (! class_exists($class)) {
            throw new DomainException("Transfer class `$class` is not found");
        }
        return $class;
    }

    public function makeTransfer(string $class): TransferInterface
    {
        $object = new $class;
        if (! $object instanceof TransferInterface) {
            throw new DomainException("Class `$class` is not instance of TransferInterface");
        }
        return $object;
    }

    public function combine(string $property, array $data): array
    {
        return array_map(fn(array $datum) => $this->getLoadedTransfer(
            $this->makeTransfer($this->getTransferClassByProperty($property)), $datum
        ), $data);
    }

    public function hasProperty(string $property): bool
    {
        return key_exists($property, $this->combinations());
    }

    public function getLoadedTransfer(TransferInterface $transfer, array $data): TransferInterface
    {
        $transfer->load($data);
        return $transfer;
    }
}
