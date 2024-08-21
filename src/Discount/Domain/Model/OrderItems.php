<?php

namespace App\Discount\Domain\Model;

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
            $total += $orderItem->getTotal();
        }
        return $total;
    }
}