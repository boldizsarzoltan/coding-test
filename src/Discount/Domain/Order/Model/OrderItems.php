<?php

namespace App\Discount\Domain\Order\Model;

use App\Shared\TypedArray;

/**
 * @extends  TypedArray<OrderItem>
 */
class OrderItems extends TypedArray
{
    protected function getType(): string
    {
        return OrderItem::class;
    }

    public function getItemsTotal(): int
    {
        $total = 0;
        /** @var OrderItem $orderItem */
        foreach ($this as $orderItem) {
            $total += $orderItem->total;
        }
        return $total;
    }

    /**
     * @return array<string>
     */
    public function getItemIds(): array
    {
        $ids = [];
        foreach ($this as $orderItem) {
            $ids[] = $orderItem->id;
        }
        return $ids;
    }
}
