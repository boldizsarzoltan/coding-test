<?php

namespace App\Tests\Unit\Product\Domain;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;
use PHPUnit\Framework\TestCase;

class ProductsTest extends TestCase
{
    public function testWorksCorrectly()
    {
        $id1 = "test_id3";
        $product1 = new Product(
            $id1,
            "",
            1,
            1,
        );
        $id2 = "test_id4";
        $product2 = new Product(
            $id2,
            "",
            1,
            1,
        );
        $id3 = "test_id5";
        $product3 = new Product(
            $id3,
            "",
            1,
            1,
        );
        $products = new Products([$product1]);
        $products->append($product2);
        $products->offsetSet(3, $product3);
        $this->assertNull($products->findById("anything other than ids"));
        $this->assertEquals($product1, $products->findById($id1));
        $this->assertEquals($product2, $products->findById($id2));
        $this->assertEquals($product3, $products->findById($id3));
        $expectedProduct = new Products();
        $expectedProduct->offsetSet($id1, $product1);
        $expectedProduct->offsetSet($id2, $product2);
        $this->assertEquals(
            $expectedProduct,
            $products->findByIds([$id1, $id2])
        );
    }
}
