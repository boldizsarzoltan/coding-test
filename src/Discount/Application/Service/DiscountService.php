<?php

namespace App\Discount\Application\Service;

use App\Discount\Domain\Model\DiscountedOrder;
use App\Discount\Domain\Model\OrderWithDiscount;

class DiscountService
{
    public function getOrderWithDiscount(DiscountedOrder $order): OrderWithDiscount
    {
        return new OrderWithDiscount();
    }
}
