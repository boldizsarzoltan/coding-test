<?php

namespace App\Discount\Application\Service;

use App\Discount\Domain\Model\OrderWithDiscount;

class DiscountOrderMapper
{
    /**
     * @param OrderWithDiscount $orderWithDiscount
     * @return array<mixed>
     */
    public function mapOrderWithDiscountToArray(OrderWithDiscount $orderWithDiscount): array
    {
        return [];
    }
}