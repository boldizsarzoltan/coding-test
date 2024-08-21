<?php

namespace App\Product\Application\Gateway;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;
use App\Product\Domain\Service\ProductRepository;

readonly class ProductMapper
{
    /**
     * @param Product $product
     * @return array{
     *     id:string,
     *     category_id:int
     * }
     */
    public function mapProductCategoryDataToArray(Product $product): array
    {
        return [
            "id" => $product->id,
            "category_id" => $product->categoryId
        ];
    }

    /**
     * @param Products<Product> $products
     * @return array<string,array{
     *     id:string,
     *     category_id:int
     * }>
     */
    public function mapProductsCategoryDataToArray(Products $products): array
    {
        $productsAsArray = [];
        /** @var string $productId  */
        foreach ($products as $productId => $product) {
            $productsAsArray[$productId] = $this->mapProductCategoryDataToArray($product);
        }
        return $productsAsArray;
    }
}
