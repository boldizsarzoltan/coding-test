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
            !isset($data['id']) || !is_string($data['id']) ||
            !isset($data['description']) || !is_string($data["description"]) || !isset($data['category']) ||
            (!is_int($data["category"]) && !ctype_digit($data['category'])) ||
            !isset($data['price']) || !is_numeric($data["price"])
        ) {
            throw new InvalidProductException();
        }
        $price = (float)$data["price"];
        return new Product(
            (string) $data['id'],
            $data["description"],
            $data["category"],
            (int) ($price * 100)
        );
    }
}
