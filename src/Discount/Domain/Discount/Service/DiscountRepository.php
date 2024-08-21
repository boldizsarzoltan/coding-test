<?php

namespace App\Discount\Domain\Discount\Service;

use App\Discount\Domain\Discount\Model\Discounts;

interface DiscountRepository
{
    public function getDiscounts(): Discounts;
}
