<?php

namespace App\Product\Application\Gateway;

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
        $products = $this->repository->getByIds($productsIds);
        return $this->mapper->mapProductsCategoryDataToArray($products);
    }
}
