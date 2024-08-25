<?php

namespace App\Discount\Domain\Model;

use App\Discount\Domain\Discount\Model\Discount;

readonly class DiscountedOrderItems
{
    public function __construct(
        public Discount $discount,
        public OrderItems $order
    ) {
    }
}
