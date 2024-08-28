<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Exception\InvalidOrderItemDataException;
use App\Discount\Domain\Model\OrderItem as DiscountOrderItem;
use App\Discount\Domain\Order\Model\OrderItem as SimpleOrderItem;
use App\Shared\Settings;

class DiscountItemMapper
{
    /**
     * @param array<string|int|null> $additionalData
     */
    public function mapOrderItemToDiscountItem(SimpleOrderItem $orderItem, array $additionalData): DiscountOrderItem
    {
        if (!isset($additionalData["category_id"])) {
            throw new InvalidOrderItemDataException();
        }
        return new DiscountOrderItem(
            $orderItem->id,
            (int) $additionalData["category_id"],
            $orderItem->quantity,
            $orderItem->unitPrice,
            Settings::DEFAULT_FREE_COUNT
        );
    }
}
