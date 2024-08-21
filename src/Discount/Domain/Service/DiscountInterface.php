<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Model\Order;

interface DiscountInterface
{
    public function isEligible(Order $order): bool;
}
