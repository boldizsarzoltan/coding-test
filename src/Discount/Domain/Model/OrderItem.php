<?php

namespace App\Discount\Domain\Model;

readonly class OrderItem
{
    public function __construct(
        public string $id,
        public int $categoryId,
        public int $quantity,
        public int $unitPrice
    ) {
    }

    public function getTotal(): int
    {
        return $this->quantity * $this->unitPrice;
    }
}
