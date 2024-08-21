<?php

namespace App\Discount\Domain\Discount;

enum DiscountRuleType: string
{
    case CustomerTotal = 'customer_total';
    case CategoryId = 'category_id';
    case MinCategoryProductCount = 'min_category_product_count';
}
