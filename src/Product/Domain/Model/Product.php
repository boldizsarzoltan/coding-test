<?php

namespace App\Product\Domain\Model;

use App\Product\Domain\Exception\InvalidProductException;
use App\Shared\Settings;

readonly class Product
{
    public function __construct(
        public string $id,
        public string $description,
        public int $categoryId,
        public int $price
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->price < Settings::MIN_PRICE) {
            throw new InvalidProductException();
        }
    }
}
