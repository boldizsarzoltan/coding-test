<?php

namespace App\Discount\Domain\Order\Model;

use App\Discount\Domain\Order\Exception\InvalidOrderItemException;

readonly class OrderItem
{
    public function __construct(
        public string $id,
        public int $quantity,
        public int $unitPrice,
        public int $total
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->total !== $this->quantity * $this->unitPrice) {
            throw new InvalidOrderItemException(
                "Order item total must be equal to quantity * price, id:{$this->id}"
            );
        }
    }
}
