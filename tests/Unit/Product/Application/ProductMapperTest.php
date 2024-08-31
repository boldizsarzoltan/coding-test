<?php

namespace App\Tests\Unit\Product\Application;

use App\Product\Application\Gateway\ProductMapper;
use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;
use PHPUnit\Framework\TestCase;

class ProductMapperTest extends TestCase
{
    private ProductMapper $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new ProductMapper();
    }

    public function testMapProductCategoryDataToArray()
    {
        $id = "test_id6";
        $categoryId = 1;
        $product = new Product(
            $id,
            "",
            $categoryId,
            1,
        );
        $this->assertEquals(
            [
                "id" => $id,
                "category_id" => $categoryId
            ],
            $this->instance->mapProductCategoryDataToArray($product)
        );
    }

    public function testMapProductsCategoryDataToArray()
    {
        $id1 = "test_id7";
        $categoryId1 = 1;
        $product1 = new Product(
            $id1,
            "",
            $categoryId1,
            1,
        );
        $id2 = "test_id8";
        $categoryId2 = 2;
        $product2 = new Product(
            $id2,
            "",
            $categoryId2,
            1,
        );
        $products = new Products();
        $products->offsetSet($id1, $product1);
        $products->offsetSet($id2, $product2);
        $this->assertEquals(
            [
                $id1 => [
                    "id" => $id1,
                    "category_id" => $categoryId1
                ],
                $id2 => [
                    "id" => $id2,
                    "category_id" => $categoryId2
                ],
            ],
            $this->instance->mapProductsCategoryDataToArray($products)
        );
    }
}
