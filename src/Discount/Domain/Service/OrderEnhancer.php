<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Model\Order as EnhancedOrder;
use App\Discount\Domain\Order\Model\Order as SimpleOrderModel;

interface OrderEnhancer
{
    public function enhanceOrder(SimpleOrderModel $order): ?EnhancedOrder;
}
