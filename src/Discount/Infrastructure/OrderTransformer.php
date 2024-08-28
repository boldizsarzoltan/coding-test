<?php

namespace App\Discount\Infrastructure;

use App\Customer\Application\Gateway\CustomerFacade;
use App\Discount\Domain\Exception\DiscountOrderException;
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

    public function transformOrderToDiscountOrder(Order $order): DiscountOrder
    {
        try {
            $discountCustomer = $this->customerFacade->getCustomerOrderData($order->customerId);
            $productsWithFullInfoAsArray = $this->productFacade->getProductCategoriesByProductsIds(
                $order->orderItems->getItemIds()
            );
        } catch (\Throwable $throwable) {
            throw new DiscountOrderException($throwable->getMessage());
        }
        try {
            $discountOrderCustomer = $this->customerMapper->mapArrayToDiscountCustomer(
                $discountCustomer
            );
            $discountOrderItems = $this->getItems($order, $productsWithFullInfoAsArray);
        } catch (\Throwable $throwable) {

        }
        return new DiscountOrder(
            $order->id,
            $discountOrderCustomer,
            $discountOrderItems
        );
    }

    /**
     * @param Order $order
     * @param array<string, array<mixed>> $productsWithFullInfoAsArray
     * @return OrderItems
     */
    public function getItems(Order $order, array $productsWithFullInfoAsArray): OrderItems
    {
        $discountOrderItems = new OrderItems();
        /** @var OrderItem $orderItem */
        foreach ($order->orderItems as $orderItem) {
            $discountOrderItem = $this->discountItemMapper->mapOrderItemToDiscountItem(
                $orderItem,
                $productsWithFullInfoAsArray[$orderItem->id]
            );
            $discountOrderItems->append($discountOrderItem);
        }
        return $discountOrderItems;
    }
}
