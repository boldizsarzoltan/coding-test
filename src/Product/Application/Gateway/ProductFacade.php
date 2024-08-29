<?php

namespace App\Product\Application\Gateway;

use App\Product\Application\Exception\ExpectedRunTimeException;
use App\Product\Application\Exception\UnexpectedRunTimeException;
use App\Product\Domain\Exception\ProductDataException;
use App\Product\Domain\Service\ProductRepository;

readonly class ProductFacade
{
    public function __construct(
        private ProductRepository $repository,
        private ProductMapper $mapper
    ) {
    }

    /**
     * @param array<string> $productsIds
     * @return array<string,array{
     *      id:string,
     *      category_id:int
     *  }>
     */
    public function getProductCategoriesByProductsIds(array $productsIds): array
    {
        try {
            $products = $this->repository->getByIds($productsIds);
            return $this->mapper->mapProductsCategoryDataToArray($products);
        } catch (ProductDataException $exception) {
            throw new ExpectedRunTimeException($exception->getMessage());
        } catch (\Throwable $exception) {
            throw new UnexpectedRunTimeException($exception->getMessage());
        }
    }
}
