<?php

namespace App\Product\Domain\Service;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;

interface ProductRepository
{
    public function getById(string $id): ?Product;

    /**
     * @param array<string> $ids
     * @return Products
     */
    public function getByIds(array $ids): Products;
}
