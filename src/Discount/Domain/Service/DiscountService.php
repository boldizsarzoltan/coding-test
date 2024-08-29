<?php

namespace App\Discount\Domain\Service;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Service\DiscountRepository;
use App\Discount\Domain\Exception\DiscountOrderDataException;
use App\Discount\Domain\Exception\DiscountOrderException;
use App\Discount\Domain\Model\DiscountedOrderItems;
use App\Discount\Domain\Model\DiscountedOrdersItems;
use App\Discount\Domain\Model\OrderWithDiscount;
use App\Discount\Domain\Order\Model\Order;

readonly class DiscountService
{
    public function __construct(
        private DiscountApplier $discountApplier,
        private DiscountVerifier $discountVerifier,
        private OrderTransformer $orderEnhancer,
        private DiscountRepository $discountRepository
    ) {
    }

    /**
     * @param Order $order
     * @return OrderWithDiscount
     * @throws DiscountOrderException
     * @throws DiscountOrderDataException
     */
    public function getDiscountedOrder(Order $order): OrderWithDiscount
    {
        $discountOrder = $this->orderEnhancer->transformOrderToDiscountOrder($order);
        $discountedOrderHistory = new DiscountedOrdersItems();
        $discounts = $this->discountRepository->getDiscounts();
        /** @var Discount $discount */
        foreach ($discounts as $discount) {
            $orderItemsEligibleForDiscount = $this->discountVerifier->getOrderItemsEligibleDiscount(
                $discountOrder,
                $discount
            );
            if (!$orderItemsEligibleForDiscount->isEmpty()) {
                continue;
            }
            $orderItemsWithAppliedDiscount = $this->discountApplier->getDiscountedOrderItems(
                $orderItemsEligibleForDiscount,
                $discount
            );
            $discountOrder = $discountOrder->getOrderWithNewItems($orderItemsWithAppliedDiscount);
            $discountedOrderHistory->append(new DiscountedOrderItems(
                $discount,
                $discountOrder->orderItems->overWriteWithNewItems($orderItemsWithAppliedDiscount)
            ));
        }
        return new OrderWithDiscount(
            $order,
            $discountedOrderHistory,
            $discountOrder
        );
    }
}
