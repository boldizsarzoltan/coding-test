<?php

namespace App\Discount\Domain\Model;

use App\Discount\Domain\Exception\InvalidOrderProductItemException;

readonly class OrderItem
{
    public function __construct(
        public string $id,
        public int $categoryId,
        public int $quantity,
        public int $unitPrice,
        public int $freeCount = 0
    ) {
        $this->validate();
    }

    public function getTotal(): int
    {
        return ($this->quantity - $this->freeCount) * $this->unitPrice;
    }

    public function overWriteWithNewUnitPrice(int $newUnitPrice): self
    {
        return new self(
            $this->id,
            $this->categoryId,
            $this->quantity,
            $this->unitPrice,
            $newUnitPrice
        );
    }

    public function overWriteFreeCount(int $freeCount): self
    {
        return new self(
            $this->id,
            $this->categoryId,
            $this->quantity,
            $freeCount
        );
    }

    private function validate(): void
    {
        if ($this->quantity >= $this->freeCount) {
            return;
        }
        throw new InvalidOrderProductItemException();
    }
}
