<?php

namespace Rbz\Data;

use DomainException;
use Rbz\Data\Collections\Error\Collection;
use Rbz\Data\Interfaces\TransferInterface;

abstract class CompositeTransfer extends Transfer
{
    /**
     * @throws DomainException
     */
    public function load($data): bool
    {
        $data = $this->normalizeData($data);
        $success = parent::load($data);
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $success = $this->getTransfer($transfer)->load($data[$transfer] ?? $data) && $success;
        }
        return $success;
    }

    public function validate(array $properties = []): bool
    {
        $validate = parent::validate($this->getTransferAttributes($properties));
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $validate = $this->getTransfer($transfer)->validate($this->getAttributesForTransfer($properties, $transfer)) && $validate;
        }
        return $validate;
    }

    public function setProperties(array $data): void
    {
        parent::setProperties($this->getThisTransferFields($data));
        foreach ($this->getDataTransfersForThisTransfer($data) as $transfer => $value) {
            $this->getTransfer($transfer)->load($value);
        }
    }

    private function getThisTransferFields(array $data): array
    {
        return array_filter_keys($data, function ($attribute) {
            return ! $this->isAdditionalTransfer($attribute);
        });
    }

    private function getDataTransfersForThisTransfer(array $data): array
    {
        return array_filter($data, function ($value, $attribute) {
            return $this->isAdditionalTransfer($attribute) && is_array($value);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function getAdditionalTransfers(): array
    {
        $additionalTransfers = [];
        foreach ($this->getProperties() as $attribute) {
            if ($this->isTransferAttribute($attribute)) {
                $additionalTransfers[] = $attribute;
            }
        }
        return $additionalTransfers;
    }

    public function isTransferAttribute($attribute): bool
    {
        if ($this->isSetProperty($attribute)) {
            return $this->getProperty($attribute) instanceof TransferInterface;
        }
        return false;
    }

    public function isAdditionalTransfer(string $transfer): bool
    {
        return in_array($transfer, $this->getAdditionalTransfers());
    }

    /**
     * @throws DomainException
     */
    public function getTransfer(string $attribute): TransferInterface
    {
        if (! $this->isTransferAttribute($attribute)) {
            throw new DomainException("Attribute `$attribute` is not implementing TransferInterface");
        }
        return $this->getProperty($attribute);
    }

    public function getErrors(): Collection
    {
        $collection = parent::getErrors();
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $collection->merge($this->getTransfer($transfer)->getErrors());
        }
        return $collection;
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }

    /** @deprecated  */
    public function getTransferAttributes(array $attributes): array
    {
        $rules = [];
        foreach ($attributes as $attribute) {
            if (count($this->explodeValidationAttributes($attribute)) == 1) {
                $rules[] = $attribute;
            }
        }
        return $rules;
    }

    /** @deprecated  */
    public function getAttributesForTransfer(array $attributes, string $transfer): array
    {
        $rules = [];
        foreach ($attributes as $attribute) {
            $exploded = $this->explodeValidationAttributes($attribute);
            if ($this->isTransferAttributes($exploded, $transfer)) {
                $this->unsetTransferAttribute($exploded, $transfer);
                $rules[] = $this->implodeValidationAttributes($exploded);
            }
        }
        return $rules;
    }

    /** @deprecated  */
    public function explodeValidationAttributes(string $rule): array
    {
        return explode('.', $rule);
    }

    /** @deprecated  */
    public function implodeValidationAttributes(array $rule): string
    {
        return implode('.', $rule);
    }

    /** @deprecated  */
    public function isTransferAttributes(array $rules, string $transfer): bool
    {
        return count($rules) > 1 && $rules[0] == $transfer;
    }

    /** @deprecated  */
    public function unsetTransferAttribute(array &$exploded, string $transfer): void
    {
        unset($exploded[array_search($transfer, $exploded)]);
    }
}
