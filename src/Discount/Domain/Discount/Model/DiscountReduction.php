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
        public int $value,
        public ?int $value2 = null
    ) {
        $this->validate();
    }
    private function validate(): void
    {
        switch ($this->discountType) {
            case DiscountReductionType::CheapestPercent:
            case DiscountReductionType::TotalPercent:
                if (!is_null($this->value2) || $this->value < 0 || $this->value > Settings::MAX_DISCOUNT_PERCENT) {
                    throw new InvalidDiscountReductionTypeException();
                }
                break;
            case DiscountReductionType::FreeCount:
                if (
                    is_null($this->value2) || $this->value2 < 1 ||
                    $this->value < 0 || $this->value > Settings::MAX_FREE_COUNT
                ) {
                    throw new InvalidDiscountRuleTypeException();
                }
                break;
            default:
                throw new UnknownDiscountRuleTypeException();
        }
    }
}
