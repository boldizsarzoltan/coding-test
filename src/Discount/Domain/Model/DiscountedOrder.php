<?php

namespace App\Discount\Domain\Model;

use App\Discount\Domain\Discount\Discount;
use App\Discount\Domain\Order\Model\Order;

readonly class DiscountedOrder
{
    public function __construct(
        Discount $discount,
        Order $order
    ) {
    }
}
