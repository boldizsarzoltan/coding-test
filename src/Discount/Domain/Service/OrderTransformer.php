<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Model\Order as DiscountOrder;
use App\Discount\Domain\Order\Model\Order;

interface OrderTransformer
{
    public function transformOrderToDiscountOrder(Order $order): ?DiscountOrder;
}
