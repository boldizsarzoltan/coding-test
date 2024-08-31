<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Exception\InvalidOrderItemDataException;
use App\Discount\Domain\Model\OrderItem as DiscountOrderItem;
use App\Discount\Domain\Order\Model\OrderItem as SimpleOrderItem;
use App\Shared\Settings;

class DiscountItemMapper
{
    /**
     * @param array<mixed> $additionalData
     * @throws InvalidOrderItemDataException
     */
    public function mapOrderItemToDiscountItem(SimpleOrderItem $orderItem, array $additionalData): DiscountOrderItem
    {
        if (
            !isset($additionalData["category_id"])
        ) {
            throw new InvalidOrderItemDataException();
        }
        if (
            !is_int($additionalData["category_id"]) && !ctype_digit($additionalData['category_id'])
        ) {
            throw new InvalidOrderItemDataException();
        }
        /** @var array{category_id:int} $additionalData */
        return new DiscountOrderItem(
            $orderItem->id,
            (int) $additionalData["category_id"],
            $orderItem->quantity,
            $orderItem->unitPrice,
            Settings::DEFAULT_FREE_COUNT
        );
    }
}
