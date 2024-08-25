<?php

namespace App\Discount\Domain\Discount\Model;

use App\Discount\Domain\Discount\Exception\InvalidDiscountReductionTypeException;
use App\Discount\Domain\Discount\Exception\InvalidDiscountRuleTypeException;
use App\Discount\Domain\Discount\Exception\UnknownDiscountRuleTypeException;
use App\Shared\Settings;

readonly class DiscountReduction
{
    public function __construct(
        public DiscountReductionType $discountType,
        public int $value
    ) {
        $this->validate();
    }
    private function validate(): void
    {
        switch ($this->discountType) {
            case DiscountReductionType::CheapestPercent:
            case DiscountReductionType::TotalPercent:
                if ($this->value < 0 || $this->value > Settings::MAX_DISCOUNT_PERCENT) {
                    throw new InvalidDiscountReductionTypeException();
                }
                break;
            case DiscountReductionType::FreeCount:
                if ($this->value < 0 || $this->value > Settings::MAX_FREE_COUNT) {
                    throw new InvalidDiscountReductionTypeException();
                }
                break;
            default:
                throw new UnknownDiscountRuleTypeException();
        }
    }
}
