<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Service\DiscountRepository;
use App\Discount\Domain\Model\DiscountedOrder;
use App\Discount\Domain\Model\DiscountedOrders;
use App\Discount\Domain\Model\OrderWithDiscount;
use App\Discount\Domain\Order\Model\Order;

readonly class DiscountService
{
    public function __construct(
        private DiscountApplier $discountApplier,
        private DiscountVerifier $discountVerifier,
        private OrderEnhancer $orderEnhancer,
        private DiscountRepository $discountRepository
    ) {
    }

    public function getDiscountedOrder(Order $order): ?OrderWithDiscount
    {
        $discountOrder = $this->orderEnhancer->enhanceOrder($order);
        if (is_null($discountOrder)) {
            return null;
        }
        $discountedOrderHistory = new DiscountedOrders();
        $discounts = $this->discountRepository->getDiscounts();
        /** @var Discount $discount */
        foreach ($discounts as $discount) {
            $orderItemsEligibleForDiscount = $this->discountVerifier->getOrderItemsEligibleDiscount($discountOrder, $discount);
            if (!$orderItemsEligibleForDiscount->isEmpty()) {
                continue;
            }
            $orderItemsWithAppliedDiscount = $this->discountApplier->getDiscountedOrder(
                $discountOrder,
                $discount
            );
            $discountOrder = new DiscountedOrder(
                $discount,
                $discountOrder->getOrderWithNewItems($orderItemsWithAppliedDiscount)
            );
            $discountedOrderHistory->append($orderWithAppliedDiscount);
        }
        return new OrderWithDiscount(
            $order,
            $discountedOrderHistory,
            $discountOrder
        );
    }
}
