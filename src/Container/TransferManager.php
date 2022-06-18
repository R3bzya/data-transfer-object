<?php

namespace Rbz\Data\Container;

use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Errors\ErrorBagInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Errors\ErrorBag;

class TransferManager
{
    private TransferStorage $storage;
    
    public function __construct()
    {
        $this->storage = new TransferStorage();
    }

    public function add(string $key, TransferInterface $transfer): void
    {
        $this->storage->add($key, $transfer);
    }

    public function get(string $key): TransferInterface
    {
        return $this->storage->get($key);
    }

    public function has(string $key): bool
    {
        return $this->storage->has($key);
    }

    /**
     * @return TransferInterface[]
     */
    public function toArray(): array
    {
        return $this->storage->toArray();
    }
    
    public function transferLoad(TransferInterface $transfer, array $data): bool
    {
        return $transfer->load($data);
    }
    
    public function transferValidate(TransferInterface $transfer, array $properties = [], bool $clearErrors = true): bool
    {
        return $transfer->validate($properties, $clearErrors);
    }
    
    public function load(string $transfer, array $data): bool
    {
        return $this->transferLoad($this->get($transfer), $data);
    }
    
    public function validate(string $transfer, array $properties = [], bool $clearErrors = true): bool
    {
        return $this->transferValidate($this->get($transfer), $properties, $clearErrors);
    }
    
    public function massiveLoad(array $data): bool
    {
        foreach ($this->toArray() as $property => $transfer) {
            $this->transferLoad($transfer, Arr::getIf($data, $property, fn($value) => Arr::is($value), []));
        }
        return $this->isLoad();
    }
    
    public function massiveValidate(array $properties = [], bool $clearErrors = true): bool
    {
        foreach ($this->toArray() as $property => $transfer) {
            $this->transferValidate($transfer, Arr::getIf($properties, $property, fn($value) => Arr::is($value), []), $clearErrors);
        }
        return $this->isValidate();
    }
    
    public function getErrors(): ErrorBagInterface
    {
        $collection = new ErrorBag();
        foreach ($this->toArray() as $property => $transfer) {
            $collection->merge($transfer->getErrors()->prependPathsWith(Path::make($property)));
        }
        return $collection;
    }
    
    public function isLoad(): bool
    {
        if ($this->storage->isEmpty()) {
            return true;
        }
        
        $isLoad = false;
        foreach ($this->toArray() as $transfer) {
            $isLoad = $isLoad || $transfer->isLoad();
        }
        return $isLoad;
    }
    
    public function isValidate(): bool
    {
        if ($this->storage->isEmpty()) {
            return true;
        }
        
        $isValidate = true;
        foreach ($this->toArray() as $transfer) {
            $isValidate = $isValidate && $transfer->isValidate();
        }
        return $isValidate;
    }
}
