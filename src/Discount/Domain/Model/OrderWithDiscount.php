<?php

namespace App\Discount\Domain\Model;

use App\Discount\Domain\Order\Model\Order;

readonly class OrderWithDiscount
{
    public function __construct(
        public Order $originalOrder,
        public DiscountedOrders $discountedOrderHistory,
        public DiscountedOrder $finalOrder
    ) {
    }
}
