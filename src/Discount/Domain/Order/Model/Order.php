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
        $idToSmall = $this->id < 1;
        $customerIdToSmall = $this->customerId < 1;
        $noOrderItems = $this->orderItems->count() < 1;
        $totalToSmall = $this->totalPrice < 1;
        $orderTotalNoTqEqualToItemTotal = $this->totalPrice != $this->orderItems->getItemsTotal();
        if (
            !$idToSmall &&
            !$customerIdToSmall &&
            !$noOrderItems &&
            !$totalToSmall &&
            !$orderTotalNoTqEqualToItemTotal
        ) {
            return;
        }
        throw new InvalidOrderException();
    }
}
