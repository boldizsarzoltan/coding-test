<?php

namespace App\Discount\Application\Service;

use App\Discount\Domain\Model\OrderItem;
use App\Discount\Domain\Model\OrderItems;
use App\Discount\Domain\Model\OrderWithDiscount;
use App\Shared\Settings;

class DiscountOrderMapper
{
    /**
     * @param OrderWithDiscount $orderWithDiscount
     * @return array{
     *     id:int,
     *     customer-id:int,
     *     items:array<array{
     *       product-id:string,
     *       quantity:int,
     *       unit-price:float,
     *       free-count:int,
     *       total:float
     *   }>,
     *     total:float
     * }
     */
    public function mapOrderWithDiscountToArray(OrderWithDiscount $orderWithDiscount): array
    {
        return [
            "id" => $orderWithDiscount->finalOrder->id,
            "customer-id" => $orderWithDiscount->finalOrder->customer->id,
            "items" => $this->mapOrderItemsWithDiscountToArray($orderWithDiscount->finalOrder->orderItems),
            "total" => $orderWithDiscount->finalOrder->orderItems->getItemsTotal() / Settings::PRICE_VALUE_MULTIPLIER
        ];
    }

    /**
     * @param OrderItems<OrderItem> $orderItems
     * @return array<array{
     *      product-id:string,
     *      quantity:int,
     *      unit-price:float,
     *      free-count:int,
     *      total:float
     *  }>
     */
    public function mapOrderItemsWithDiscountToArray(OrderItems $orderItems): array
    {
        $result = [];
        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $result[] = $this->mapOrderItemWithDiscountToArray($orderItem);
        }
        return $result;
    }

    /**
     * @param OrderItem $orderItem
     * @return array{
     *     product-id:string,
     *     quantity:int,
     *     unit-price:float,
     *     free-count:int,
     *     total:float
     * }
     */
    private function mapOrderItemWithDiscountToArray(OrderItem $orderItem): array
    {
        return [
            "product-id" => $orderItem->id,
            "quantity" => $orderItem->quantity,
            "unit-price" => $orderItem->unitPrice / Settings::PRICE_VALUE_MULTIPLIER,
            "free-count" => $orderItem->freeCount,
            "total" => $orderItem->getTotal() / Settings::PRICE_VALUE_MULTIPLIER
        ];
    }
}
