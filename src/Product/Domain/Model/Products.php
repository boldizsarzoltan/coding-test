<?php

namespace App\Product\Domain\Model;

use App\Shared\TypedArray;

/**
 * @extends  TypedArray<Product>
 */
class Products extends TypedArray
{
    public function findById(string $idToFind): ?Product
    {
        /** @var Product $products */
        foreach ($this->getArrayCopy() as $products) {
            if ($products->id === $idToFind) {
                return $products;
            }
        }
        return null;
    }

    /**
     * @param array<string> $idsToFind
     * @return Products<string, Product>
     */
    public function findByIds(array $idsToFind): Products // @phpstan-ignore-line
    {
        $foundProducts = new Products();
        /** @var Product $product */
        foreach ($this->getArrayCopy() as $product) {
            if (!in_array($product->id, $idsToFind)) {
                continue;
            }
            $foundProducts->offsetSet($product->id, $product); // @phpstan-ignore-line
        }
        return $foundProducts;
    }

    protected function getType(): string
    {
        return Product::class;
    }
}
