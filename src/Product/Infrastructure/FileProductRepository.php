<?php

namespace App\Product\Infrastructure;

use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Model\Customers;
use App\Customer\Domain\Service\CustomerRepository;
use App\Customer\Infrastructure\CustomerMapper;
use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;
use App\Product\Domain\Service\ProductRepository;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileProductRepository implements ProductRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private ProductMapper $productMapper
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getByIds(array $ids): Products
    {
        $products = $this->getProducts();
        if (is_null($products)) {
            return new Products();
        }
        return $products->findByIds($ids);
    }

    public function getById(string $id): ?Product
    {
        $products = $this->getProducts();
        if (is_null($products)) {
            return null;
        }
        return $products->findById($id);
    }

    /**
     * @return null|Products<Product>
     */
    public function getProducts(): ?Products
    {
        if (!$this->filesystem->exists('data/products.json')) {
            return null;
        }
        $rawProductsData = $this->filesystem->readFile('data/products.json');
        if (!json_validate($rawProductsData)) {
            return null;
        }
        $customersData = json_decode($rawProductsData, true);
        if (!is_array($customersData)) {
            return null;
        }
        $products = new Products();
        foreach ($customersData as $customerData) {
            $product = $this->productMapper->mapProductFromArray($customerData);
            $products->append($product);
        }
        return $products;
    }
}
