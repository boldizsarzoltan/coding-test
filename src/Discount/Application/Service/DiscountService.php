<?php

namespace App\Discount\Application\Service;

use App\Discount\Domain\Model\Order;
use App\Discount\Domain\Model\OrderWithDiscount;

class DiscountService
{
    public function getOrderWithDiscount(Order $order): OrderWithDiscount
    {
        return new OrderWithDiscount();
    }
}
