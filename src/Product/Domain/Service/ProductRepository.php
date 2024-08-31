<?php

namespace App\Product\Domain\Service;

use App\Product\Domain\Exception\ProductDataException;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;

interface ProductRepository
{
    /**
     * @param string $id
     * @return Product
     * @throws ProductDataException
     * @throws ProductNotFoundException
     */
    public function getById(string $id): Product;

    /**
     * @param array<string> $ids
     * @return Products
     * @throws ProductDataException
     */
    public function getByIds(array $ids): Products;
}
