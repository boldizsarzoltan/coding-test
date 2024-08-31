<?php

namespace App\Tests\Unit\Product\Application;

use App\Product\Application\Exception\ExpectedRunTimeException;
use App\Product\Application\Exception\UnexpectedRunTimeException;
use App\Product\Application\Gateway\ProductFacade;
use App\Product\Application\Gateway\ProductMapper;
use App\Product\Domain\Exception\ProductDataException;
use App\Product\Domain\Model\Products;
use App\Product\Domain\Service\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductFacadeTest extends TestCase
{
    private MockObject&ProductRepository $productRepositoryMock;
    private MockObject&ProductMapper $productMapperMock;
    private ProductFacade $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->productMapperMock = $this->createMock(ProductMapper::class);
        $this->instance = new ProductFacade(
            $this->productRepositoryMock,
            $this->productMapperMock
        );
    }


    public function testCatchesProductDataExceptionFromRepository()
    {
        $randomEntryData = [];
        $this->expectException(ExpectedRunTimeException::class);
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getByIds')
            ->with($randomEntryData)
            ->willThrowException(new ProductDataException());
        $this->productMapperMock
            ->expects(self::never())
            ->method('mapProductsCategoryDataToArray');
        $this->instance->getProductCategoriesByProductsIds($randomEntryData);
    }

    public function testCatchesProductDataExceptionFromMapper()
    {
        $randomEntryData = [];
        $products = new Products();
        $this->expectException(ExpectedRunTimeException::class);
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getByIds')
            ->with($randomEntryData)
            ->willReturn($products);
        $this->productMapperMock
            ->expects(self::once())
            ->method('mapProductsCategoryDataToArray')
            ->with($products)
            ->willThrowException(new ProductDataException());
        $this->instance->getProductCategoriesByProductsIds($randomEntryData);
    }

    public function testCatchesGeneralFromRepository()
    {
        $randomEntryData = [];
        $this->expectException(UnexpectedRunTimeException::class);
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getByIds')
            ->with($randomEntryData)
            ->willThrowException(new \Exception());
        $this->productMapperMock
            ->expects(self::never())
            ->method('mapProductsCategoryDataToArray');
        $this->instance->getProductCategoriesByProductsIds($randomEntryData);
    }

    public function testCatchesGeneralFromMapper()
    {
        $randomEntryData = [];
        $products = new Products();
        $this->expectException(UnexpectedRunTimeException::class);
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getByIds')
            ->with($randomEntryData)
            ->willReturn($products);
        $this->productMapperMock
            ->expects(self::once())
            ->method('mapProductsCategoryDataToArray')
            ->with($products)
            ->willThrowException(new \Exception());
        $this->instance->getProductCategoriesByProductsIds($randomEntryData);
    }

    public function testWorksCorrectly()
    {
        $randomEntryData = [];
        $finalResult = ["finalResult"];
        $products = new Products();
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('getByIds')
            ->with($randomEntryData)
            ->willReturn($products);
        $this->productMapperMock
            ->expects(self::once())
            ->method('mapProductsCategoryDataToArray')
            ->with($products)
            ->willReturn($finalResult);
        $this->assertEquals($finalResult, $this->instance->getProductCategoriesByProductsIds($randomEntryData));
    }
}
