<?php

namespace App\Discount\Infrastructure\Discount;

use App\Discount\Domain\Discount\Model\Discount;
use App\Discount\Domain\Discount\Model\DiscountReduction;
use App\Discount\Domain\Discount\Model\DiscountReductionType;
use App\Discount\Domain\Discount\Model\DiscountRule;
use App\Discount\Domain\Discount\Model\DiscountRuleType;
use App\Discount\Domain\Discount\Model\Discounts;
use App\Discount\Domain\Discount\Service\DiscountRepository;

class StaticDiscountRepository implements DiscountRepository
{
    public function getDiscounts(): Discounts
    {
        $discounts = new Discounts();
        $customerHas1000Total = new DiscountRule(
            DiscountRuleType::CustomerTotal,
            100000 // each value that is a price should be *100
        );
        $discountOf10Percent = new DiscountReduction(
            DiscountReductionType::TotalPercent,
            10
        );
        $customerHas1000TotalAndGets10Percent = new Discount(
            $customerHas1000Total,
            $discountOf10Percent
        );
        $discounts->append($customerHas1000TotalAndGets10Percent);
        $orderProductHasCategoryId1AndTotalProductQuantity2 = new DiscountRule(
            DiscountRuleType::IndividualProductCategoryId,
            2
        );
        $buyAtLeast5Get1Free = new DiscountReduction(
            DiscountReductionType::FreeCount,
            5,
            1
        );
        $orderProductHasId2buyAtLeast5Get1Free = new Discount(
            $orderProductHasCategoryId1AndTotalProductQuantity2,
            $buyAtLeast5Get1Free
        );
        $discounts->append($orderProductHasId2buyAtLeast5Get1Free);
        $orderProductHasCategoryId1AndTotalProductQuantity2 = new DiscountRule(
            DiscountRuleType::MinCategoryProductsCount,
            1,
            2
        );
        $buyAtLeast5Get1Free = new DiscountReduction(
            DiscountReductionType::CheapestPercent,
            20
        );
        $orderProductHasCategoryId1AndTotalProductQuantity2 = new Discount(
            $orderProductHasCategoryId1AndTotalProductQuantity2,
            $buyAtLeast5Get1Free
        );
        $discounts->append($orderProductHasCategoryId1AndTotalProductQuantity2);
        return $discounts;
    }
}
