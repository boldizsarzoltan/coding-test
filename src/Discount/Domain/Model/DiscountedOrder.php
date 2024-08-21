<?php

namespace App\Discount\Domain\Model;

use App\Discount\Domain\Discount\Model\Discount;

readonly class DiscountedOrder
{
    public function __construct(
        public Discount $discount,
        public Order $order
    ) {
    }
}
