<?php

namespace App\Tests\Unit\Product\Domain;

use App\Product\Domain\Exception\InvalidProductException;
use App\Product\Domain\Model\Product;
use App\Product\Infrastructure\ProductMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testThrowsExceptionIfValueSmallerThanMinPermitted()
    {
        $this->expectException(InvalidProductException::class);
        new Product(
            "test_id2",
            "",
            1,
            -1,
        );
    }
}
