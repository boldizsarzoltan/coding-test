<?php

namespace App\Discount\Domain\Discount;

readonly class DiscountRule
{
    public function __construct(
        DiscountRuleType $discountRuleType,
        int $value,
        ?int $value2
    ) {
        $this->validate();
    }
}
