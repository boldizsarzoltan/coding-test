<?php

namespace App\Discount\Domain\Discount\Model;

use App\Discount\Domain\Discount\Exception\InvalidDiscountRuleTypeException;
use App\Discount\Domain\Discount\Exception\UnknownDiscountRuleTypeException;

readonly class DiscountRule
{
    public function __construct(
        public DiscountRuleType $discountRuleType,
        public int $value,
        public ?int $value2 = null
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        switch ($this->discountRuleType) {
            case DiscountRuleType::CustomerTotal:
                if (!is_null($this->value2)) {
                    throw new InvalidDiscountRuleTypeException();
                }
                break;
            case DiscountRuleType::IndividualProductCategoryId:
            case DiscountRuleType::MinCategoryProductsCount:
                if (is_null($this->value2) || $this->value2 < 1) {
                    throw new InvalidDiscountRuleTypeException();
                }
                break;
            default:
                throw new UnknownDiscountRuleTypeException();
        }
    }
}
