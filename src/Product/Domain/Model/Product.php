<?php

namespace App\Product\Domain\Model;

use App\Discount\Domain\Model\DiscountedOrder;

readonly class Product
{
    public function __construct(
        public string $id,
        public string $description,
        public int $categoryId,
        public int $price
    ) {
    }
}
