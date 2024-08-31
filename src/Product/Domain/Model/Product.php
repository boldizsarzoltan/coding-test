<?php

namespace App\Product\Domain\Model;

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
