<?php

namespace App\Discount\Domain\Model;

use App\Discount\Domain\Order\Model\Order;
use App\Discount\Domain\Model\Order as OrderWithDiscountsApplied;

readonly class OrderWithDiscount
{
    public function __construct(
        public Order                     $originalOrder,
        public DiscountedOrdersItems     $discountedOrderHistory,
        public OrderWithDiscountsApplied $finalOrder
    ) {
    }
}
