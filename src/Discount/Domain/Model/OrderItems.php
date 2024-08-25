<?php

namespace App\Discount\Domain\Model;

use App\Shared\TypedArray;

/**
 * @extends  TypedArray<OrderItem>
 */
class OrderItems extends TypedArray
{
    public function isEmpty(): bool
    {
        return $this->count() <= 0;
    }

    public function overWriteWithNewItems(OrderItems $newOrderItems): self
    {
        $newOrderItems = new self($newOrderItems->getArrayCopy());
        $overWriteProductIds = $newOrderItems->getProductIds();
        /** @var OrderItem $originalOrderItem */
        foreach ($this as $originalOrderItem) {
            if (!in_array($originalOrderItem->id, $overWriteProductIds)) {
                $newOrderItems->append($originalOrderItem);
            }
        }
        return $newOrderItems;
    }

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

    /**
     * @return array<string>
     */
    private function getProductIds(): array
    {
        $productIds = [];
        /** @var OrderItem $orderItem */
        foreach ($this as $orderItem) {
            $productIds[] = $orderItem->id;
        }
        return  $productIds;
    }
}
