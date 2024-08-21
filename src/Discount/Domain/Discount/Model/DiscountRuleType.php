<?php

namespace App\Discount\Domain\Discount\Model;

enum DiscountRuleType: string
{
    case CustomerTotal = 'customer_total';
    case IndividualProductCategoryId = 'individual_product_category_id';
    case MinCategoryProductsCount = 'min_category_products_count';
}
