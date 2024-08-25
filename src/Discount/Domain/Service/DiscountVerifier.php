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
    ): bool {
        return $discountRule->value >= $order->customer->total;
    }

    private function orderProductsHaveCategoryId(Order $order, DiscountRule $discountRule): bool
    {
        /** @var OrderItem $item */
        foreach ($order->orderItems as $item) {
            if ($item->categoryId === $discountRule->value) {
                return true;
            }
        }
        return false;
    }

    private function orderHasMinAmountOfProductsFromCategory(Order $order, DiscountRule $discountRule): bool
    {
        $categoryProductCount = 0;
        foreach ($order->orderItems as $item) {
            if ($item->categoryId === $discountRule->value) {
                $categoryProductCount += $item->quantity;
            }
        }
        return $categoryProductCount >= (int) $discountRule->value2;
    }
}
