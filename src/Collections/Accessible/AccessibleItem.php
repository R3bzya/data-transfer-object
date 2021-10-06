<?php

namespace Rbz\DataTransfer\Collections\Accessible;

use Illuminate\Contracts\Support\Arrayable;

/** @deprecated  */
class AccessibleItem implements Arrayable
{
    private string $attribute;
    private bool $exclude;

    /**
     * @param string $attribute
     * @param bool $exclude
     */
    public function __construct(string $attribute, bool $exclude)
    {
        $this->attribute = $attribute;
        $this->exclude = $exclude;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function isExclude(): bool
    {
        return $this->exclude;
    }

    public function isNotExclude(): bool
    {
        return ! $this->exclude;
    }

    public function exclude(): void
    {
        $this->exclude = true;
    }

    public function toArray(): array
    {
        return [
            'attribute' => $this->attribute,
            'exclude' => $this->exclude
        ];
    }
}
