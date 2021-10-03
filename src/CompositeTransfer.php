<?php

namespace Rbz\DataTransfer;

use DomainException;
use Rbz\DataTransfer\Collections\Error\ErrorCollection;
use Rbz\DataTransfer\Interfaces\TransferInterface;

abstract class CompositeTransfer extends Transfer
{
    /**
     * @throws DomainException
     */
    public function load($data): bool
    {
        $data = $this->dataToArray($data);
        $success = parent::load($data);
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $success = $this->getTransfer($transfer)->load($data[$transfer] ?? $data) && $success;
        }
        return $success;
    }

    public function validate(array $attributes = []): bool
    {
        $validate = parent::validate($this->getTransferAttributes($attributes));
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $validate = $this->getTransfer($transfer)->validate($this->getAttributesForTransfer($attributes, $transfer)) && $validate;
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

    public function getErrors(): ErrorCollection
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

    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->getErrors()->getFirstMessage($attribute);
    }

    public function getErrorCount(): int
    {
        $count = parent::getErrorCount();
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $count += $this->getTransfer($transfer)->getErrors()->count();
        }
        return $count;
    }

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

    public function explodeValidationAttributes(string $rule): array
    {
        return explode('.', $rule);
    }

    public function implodeValidationAttributes(array $rule): string
    {
        return implode('.', $rule);
    }

    public function isTransferAttributes(array $rules, string $transfer): bool
    {
        return count($rules) > 1 && $rules[0] == $transfer;
    }

    public function unsetTransferAttribute(array &$exploded, string $transfer): void
    {
        unset($exploded[array_search($transfer, $exploded)]);
    }
}
