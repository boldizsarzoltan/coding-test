<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Model\DiscountReduction;
use App\Discount\Domain\Discount\Model\DiscountReductionType;
use App\Discount\Domain\Model\DiscountedOrder;
use App\Discount\Domain\Model\Order;

class DiscountApplier
{
    public function getDiscountedOrder(Order $order, Discount $discount): DiscountedOrder
    {
        return new DiscountedOrder(
            $discount,
            $this->applyDiscountToOrder($order, $discount->discountReduction)
        );
    }

    private function applyDiscountToOrder(Order $order, DiscountReduction $discountReduction): Order
    {
        return match ($discountReduction->discountType) {
            DiscountReductionType::FreeCount => $this->applyFreeCount(
                $order,
                $discountReduction->value,
                (int) $discountReduction->value2
            ),
            DiscountReductionType::CheapestPercent => $this->applyCheapestPercent(
                $order,
                $discountReduction->value
            ),
            DiscountReductionType::TotalPercent => $this->applyTotalPercent(
                $order,
                $discountReduction->value
            ),
        };
    }

    private function applyFreeCount(Order $order, int $minProductCount, int $countToBeFree): Order
    {
        return $order;
    }

    private function applyCheapestPercent(Order $order, int $value): Order
    {
        return $order;
    }

    private function applyTotalPercent(Order $order, int $value): Order
    {
        return $order;
    }
}
