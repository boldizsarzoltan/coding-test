<?php

namespace App\Discount\Domain\Discount;

readonly class DiscountReduction
{
    public function __construct(
        public DiscountReductionType $discountType,
    ) {
    }
}
