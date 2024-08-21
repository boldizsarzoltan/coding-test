<?php

namespace App\Product\Infrastructure;

use App\Product\Domain\Exception\InvalidProductException;
use App\Product\Domain\Model\Product;

readonly class ProductMapper
{
    /**
     * @param array<mixed> $data
     * @return Product
     */
    public function mapProductFromArray(array $data): Product
    {
        if (
            empty($data['id']) || !is_string($data['id']) ||
            empty($data['description']) || !is_string($data["description"]) || empty($data['category']) ||
            (!is_int($data["category"]) && !ctype_digit($data['category'])) ||
            empty($data['price']) || !is_numeric($data["price"])
        ) {
            throw new InvalidProductException();
        }
        $price = (float)$data["price"];
        return new Product(
            $data['id'],
            $data["description"],
            $data["category"],
            (int) ($price * 100)
        );
    }
}
