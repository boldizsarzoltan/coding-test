<?php

namespace App\Discount\Infrastructure;

use App\Customer\Application\Gateway\CustomerFacade;
use App\Discount\Domain\Model\DiscountedOrder;
use App\Discount\Domain\Model\Order as DiscountOrder;
use App\Discount\Domain\Order\Model\Order;
use App\Discount\Domain\Model\OrderItems;
use App\Discount\Domain\Order\Model\OrderItem;
use App\Discount\Domain\Service\OrderTransformer as OrderEnhancerInterface;
use App\Product\Application\Gateway\ProductFacade;

readonly class OrderTransformer implements OrderEnhancerInterface
{
    public function __construct(
        private CustomerFacade $customerFacade,
        private ProductFacade $productFacade,
        private DiscountCustomerMapper $customerMapper,
        private DiscountItemMapper $discountItemMapper
    ) {
    }

    public function transformOrderToDiscountOrder(Order $order): ?DiscountOrder
    {
        $discountCustomer = $this->customerFacade->getCustomerOrderData($order->customerId);
        if (is_null($discountCustomer)) {
            return null;
        }
        $discountOrderCustomer = $this->customerMapper->mapArrayToDiscountCustomer(
            $discountCustomer
        );
        if (is_null($discountOrderCustomer)) {
            return null;
        }
        $productsWithFullInfoAsArray = $this->productFacade->getProductCategoriesByProductsIds(
            $order->orderItems->getItemIds()
        );
        $dicountOrderItems = new OrderItems();
        /** @var OrderItem $orderItem */
        foreach ($order->orderItems as $orderItem) {
            $discountOrderItem = $this->discountItemMapper->mapOrderItemToDiscountItem(
                $orderItem,
                $productsWithFullInfoAsArray[$orderItem->id] ?? []
            );
            if (is_null($discountOrderItem)) {
                return null;
            }
            $dicountOrderItems->append($discountOrderItem);
        }
        return new DiscountOrder(
            $order->id,
            $discountOrderCustomer,
            $dicountOrderItems
        );
    }
}
