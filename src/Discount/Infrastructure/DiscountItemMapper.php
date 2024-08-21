<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Model\OrderItem as DiscountOrderItem;
use App\Discount\Domain\Order\Model\OrderItem as SimpleOrderItem;

class DiscountItemMapper
{
    /**
     * @param array<string|int|null> $additionalData
     */
    public function mapOrderItemToDiscountItem(SimpleOrderItem $orderItem, array $additionalData): ?DiscountOrderItem
    {
        if (!isset($additionalData["category_id"])) {
            return null;
        }
        return new DiscountOrderItem(
            $orderItem->id,
            (int) $additionalData["category_id"],
            $orderItem->quantity,
            $orderItem->unitPrice
        );
    }
}
