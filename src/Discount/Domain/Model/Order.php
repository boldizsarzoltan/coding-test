<?php

namespace App\Discount\Domain\Model;

readonly class Order
{
    public function __construct(
        public int $id,
        public Customer $customer,
        public OrderItems $orderItems
    ) {
    }

    public function getOrderWithNewItems(OrderItems $newOrderItems): self
    {
        return new self(
            $this->id,
            $this->customer,
            $newOrderItems->overWriteWithNewItems($newOrderItems)
        );
    }
}
