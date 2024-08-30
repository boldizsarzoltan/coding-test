<?php

namespace App\Discount\Domain\Order\Model;

use App\Discount\Domain\Order\Exception\InvalidOrderException;

readonly class Order
{
    public function __construct(
        public int $id,
        public int $customerId,
        public OrderItems $orderItems,
        public int $totalPrice
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->id < 1) {
            throw new InvalidOrderException("Order id must be greater than 0");
        }
        if ($this->customerId < 1) {
            throw new InvalidOrderException("Customer id must be greater than 0");
        }
        if ($this->orderItems->count() < 1) {
            throw new InvalidOrderException("Order must have at least one item");
        }
        if ($this->totalPrice < 1) {
            throw new InvalidOrderException("Order total must be greater than 0");
        }
        if (
            $this->totalPrice != $this->orderItems->getItemsTotal()
        ) {
            throw new InvalidOrderException("Order item total must be equal to order total");
        }
    }
}
