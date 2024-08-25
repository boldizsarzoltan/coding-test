<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Model\DiscountRule;
use App\Discount\Domain\Discount\Model\DiscountRuleType;
use App\Discount\Domain\Model\Order;
use App\Discount\Domain\Model\OrderItem;
use App\Discount\Domain\Model\OrderItems;

class DiscountVerifier
{
    public function getOrderItemsEligibleDiscount(Order $order, Discount $discount): OrderItems
    {
        return match ($discount->discountRule->discountRuleType) {
            DiscountRuleType::CustomerTotal => $this->customerHasMinTotal($order, $discount->discountRule),
            DiscountRuleType::IndividualProductCategoryId => $this->orderProductsHaveCategoryId(
                $order,
                $discount->discountRule
            ),
            DiscountRuleType::MinCategoryProductsCount => $this->orderHasMinAmountOfProductsFromCategory(
                $order,
                $discount->discountRule
            )
        };
    }

    private function customerHasMinTotal(
        Order $order,
        DiscountRule $discountRule
    ): OrderItems {
        if ($discountRule->value >= $order->customer->total) {
            return new OrderItems();
        }
        return $order->orderItems;
    }

    private function orderProductsHaveCategoryId(Order $order, DiscountRule $discountRule): OrderItems
    {
        $categoryProductsCount = 0;
        /** @var OrderItem $item */
        foreach ($order->orderItems as $item) {
            if ($item->categoryId === $discountRule->value && $item->hasNotFreeQuantity()) {
                $categoryProductsCount++;
            }
        }
        if ($categoryProductsCount <= $discountRule->value2) {
            return new OrderItems();
        }
        $categoryOrderItems = new OrderItems();
        foreach ($order->orderItems as $item) {
            if ($item->categoryId === $discountRule->value && $item->hasNotFreeQuantity()) {
                $categoryOrderItems->append($item);
            }
        }
        return $categoryOrderItems;
    }

    private function orderHasMinAmountOfProductsFromCategory(Order $order, DiscountRule $discountRule): OrderItems
    {
        $categoryOrderItems = new OrderItems();
        /** @var OrderItem $item */
        foreach ($order->orderItems as $item) {
            if (
                $item->categoryId === $discountRule->value &&
                $item->getNotFreeQuantity() >= (int) $discountRule->value2
            ) {
                $categoryOrderItems->append($item);
            }
        }
        return $categoryOrderItems;
    }
}
