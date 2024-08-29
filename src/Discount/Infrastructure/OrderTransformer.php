<?php

namespace App\Discount\Infrastructure;

use App\Customer\Application\Gateway\CustomerFacade;
use App\Customer\Domain\Exception\CustomerNotFoundException;
use App\Discount\Domain\Exception\DiscountOrderDataException;
use App\Discount\Domain\Exception\DiscountOrderDataMappingException;
use App\Discount\Domain\Exception\DiscountOrderException;
use App\Discount\Domain\Exception\InvalidOrderItemDataException;
use App\Discount\Domain\Model\Customer;
use App\Discount\Domain\Model\Order as DiscountOrder;
use App\Discount\Domain\Order\Model\Order;
use App\Discount\Domain\Model\OrderItems as DiscountOrderItems;
use App\Discount\Domain\Model\OrderItem as DiscountOrderItem;
use App\Discount\Domain\Order\Model\OrderItem;
use App\Discount\Domain\Order\Model\OrderItems;
use App\Discount\Domain\Service\OrderTransformer as OrderEnhancerInterface;
use App\Product\Application\Exception\ExpectedRunTimeException;
use App\Product\Application\Exception\UnexpectedRunTimeException;
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
        $discountCustomer = $this->getCustomerOrderData($order);
        $discountOrderCustomer = $this->toDiscountCustomer($discountCustomer);

        $productsWithFullInfoAsArray = $this->getOrderProductsCategoryData($order->orderItems);
        $discountOrderItems = $this->getItems($order, $productsWithFullInfoAsArray);
        return new DiscountOrder(
            $order->id,
            $discountOrderCustomer,
            $discountOrderItems
        );
    }

    /**
     * @param Order $order
     * @param array<string, array<mixed>> $productsWithFullInfoAsArray
     * @return DiscountOrderItems<DiscountOrderItem>
     * @throws DiscountOrderDataMappingException
     */
    public function getItems(Order $order, array $productsWithFullInfoAsArray): DiscountOrderItems
    {
        try {
            $discountOrderItems = new DiscountOrderItems();
            /** @var OrderItem $orderItem */
            foreach ($order->orderItems as $orderItem) {
                $discountOrderItem = $this->discountItemMapper->mapOrderItemToDiscountItem(
                    $orderItem,
                    $productsWithFullInfoAsArray[$orderItem->id]
                );
                $discountOrderItems->append($discountOrderItem);
            }
            return $discountOrderItems;
        } catch (InvalidOrderItemDataException $exception) {
            throw new DiscountOrderDataException($exception->getMessage());
        } catch (\Throwable $throwable) {
            throw new DiscountOrderException($throwable->getMessage());
        }
    }

    /**
     * @param Order $order
     * @return array<mixed>
     * @throws DiscountOrderDataException
     * @throws DiscountOrderException
     */
    public function getCustomerOrderData(Order $order): array
    {
        try {
            $discountCustomer = $this->customerFacade->getCustomerOrderData($order->customerId);
        } catch (CustomerNotFoundException $throwable) {
            throw new DiscountOrderDataException($throwable->getMessage());
        } catch (\Throwable $throwable) {
            throw new DiscountOrderException($throwable->getMessage());
        }
        return $discountCustomer;
    }

    /**
     * @param array<mixed> $discountCustomer
     */
    public function toDiscountCustomer(array $discountCustomer): Customer
    {
        try {
            $discountOrderCustomer = $this->customerMapper->mapArrayToDiscountCustomer(
                $discountCustomer
            );
        } catch (\Throwable $throwable) {
            throw new DiscountOrderDataException($throwable->getMessage());
        }
        return $discountOrderCustomer;
    }

    /**
     * @return array<string,array<mixed>>
     * @throws DiscountOrderDataException
     */
    public function getOrderProductsCategoryData(OrderItems $orderItems): array
    {
        try {
            return $this->productFacade->getProductCategoriesByProductsIds(
                $orderItems->getItemIds()
            );
        } catch (\Throwable $throwable) {
            throw new DiscountOrderDataException($throwable->getMessage());
        }
    }
}
