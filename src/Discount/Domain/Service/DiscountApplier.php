<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Model\DiscountReductionType;
use App\Discount\Domain\Model\OrderItem;
use App\Discount\Domain\Model\OrderItems;

class DiscountApplier
{
    public function getDiscountedOrderItems(OrderItems $orderItems, Discount $discount): OrderItems
    {
        return match ($discount->discountReduction->discountType) {
            DiscountReductionType::FreeCount => $this->applyFreeCount(
                $orderItems,
                $discount->discountReduction->value,
            ),
            DiscountReductionType::CheapestPercent => $this->applyCheapestPercent(
                $orderItems,
                $discount->discountReduction->value
            ),
            DiscountReductionType::TotalPercent => $this->applyTotalPercent(
                $orderItems,
                $discount->discountReduction->value
            ),
        };
    }

    private function applyFreeCount(OrderItems $orderItems, int $countToBeFree): OrderItems
    {
        $orderItemsWithDiscount = new OrderItems();
        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $orderItemsWithDiscount->append(
                $orderItem->addQuantity($countToBeFree)->addFreeCount($countToBeFree)
            );
        }
        return $orderItemsWithDiscount;
    }

    private function applyCheapestPercent(OrderItems $orderItems, int $value): OrderItems
    {
        $newOrderItems = new OrderItems();
        $productId = 0;
        $minToTalPrice = 0;
        foreach ($orderItems as $orderItem) {
            if (($minToTalPrice === 0 || $orderItem->getTotal() < $minToTalPrice) && $orderItem->hasNotFreeQuantity()) {
                $minToTalPrice = $orderItem->getTotal();
                $productId = $orderItem->id;
            }
        }
        /** @var OrderItem $originalOrderItem */
        foreach ($orderItems as $originalOrderItem) {
            if ($productId !== $originalOrderItem->id) {
                $newOrderItems->append($originalOrderItem);
            } else {
                $newUnitPrice = $originalOrderItem->unitPrice * (100 - $value) / 100;
                $orderItemWithDiscount = $originalOrderItem->overWriteWithNewUnitPrice($newUnitPrice);
                $newOrderItems->append($orderItemWithDiscount);
            }
        }

        return $newOrderItems;
    }

    private function applyTotalPercent(OrderItems $orderItems, int $value): OrderItems
    {
        if ($value < 0) {
            return $orderItems;
        }
        $orderItemsWithDiscount = new OrderItems();
        /** @var OrderItem $originalOrderItem */
        foreach ($orderItems as $originalOrderItem) {
            $newUnitPrice = $this->getRoundedPrice($originalOrderItem->unitPrice, $value);
            $newOrderItem = $originalOrderItem->overWriteWithNewUnitPrice($newUnitPrice);
            $orderItemsWithDiscount->append($newOrderItem);
        }
        return $orderItemsWithDiscount;
    }

    public function getRoundedPrice(int $unitPrice, int $value): int
    {
        return $unitPrice - (int) round($unitPrice / $value, PHP_ROUND_HALF_UP);
    }
}
