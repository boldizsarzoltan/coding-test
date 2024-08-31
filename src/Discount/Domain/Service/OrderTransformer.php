<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Exception\DiscountOrderException;
use App\Discount\Domain\Model\Order as DiscountOrder;
use App\Discount\Domain\Order\Model\Order;

interface OrderTransformer
{
    /**
     * @param Order $order
     * @return DiscountOrder
     * @throws DiscountOrderException
     */
    public function transformOrderToDiscountOrder(Order $order): DiscountOrder;
}
