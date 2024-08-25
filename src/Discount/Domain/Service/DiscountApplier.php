<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Model\DiscountReduction;
use App\Discount\Domain\Discount\Model\DiscountReductionType;
use App\Discount\Domain\Model\DiscountedOrder;
use App\Discount\Domain\Model\Order;
use App\Discount\Domain\Model\OrderItem;
use App\Discount\Domain\Model\OrderItems;

class DiscountApplier
{
    public function getDiscountedOrder(Order $order, Discount $discount): OrderItems
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
        $newOrderItems = new OrderItems();
        $productId = 0;
        $minToTalPrice = 0;
        foreach ($order->orderItems as $orderItem) {
            if($minToTalPrice !== 0 && $orderItem->getTotal() < $minToTalPrice) {
                $minToTalPrice = $orderItem->getTotal();
                $productId = $orderItem->id;
            }
        }
        foreach ($order->orderItems as $originalOrderItem) {
            if($productId !== $originalOrderItem->getProductId()) {
                $newOrderItems->append($originalOrderItem);
            }
            else {
                $newUnitPrice = $this->getRoundedPrice($originalOrderItem->unitPrice, $value);
                $orderItemWithDiscount = new OrderItem(
                    $originalOrderItem->id,
                    $originalOrderItem->categoryId,
                    $originalOrderItem->quantity,
                    $newUnitPrice,
                );
                $newOrderItems->append($orderItemWithDiscount);
            }
        }

        return $order;
    }

    private function applyTotalPercent(Order $order, int $value): Order
    {
        if($value < 0 {
            // TODO: add exception
            return $order;
        }
        $orderItemsWithDiscount = new OrderItems();
        /** @var OrderItem $orderItem */
        foreach ($order->orderItems as $originalOrderItem) {
            $newUnitPrice = $this->getRoundedPrice($originalOrderItem->unitPrice, $value);
            $newOrderItem = new OrderItem(
                $originalOrderItem->id,
                $originalOrderItem->categoryId,
                $originalOrderItem->quantity,
                $newUnitPrice,
            );
            $orderItemsWithDiscount->append($newOrderItem);
        }
        return new Order(
            $order->id,
            $order->customer,
            $orderItemsWithDiscount
        );
    }

    public function getRoundedPrice(int $unitPrice, int $value): int
    {
        return (int) round($unitPrice / $value, PHP_ROUND_HALF_UP);
    }
}
