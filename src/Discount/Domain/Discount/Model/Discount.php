<?php

namespace App\Discount\Domain\Discount\Model;

readonly class Discount
{
    public function __construct(
        public DiscountRule $discountRule,
        public DiscountReduction $discountReduction
    ) {
    }
}
