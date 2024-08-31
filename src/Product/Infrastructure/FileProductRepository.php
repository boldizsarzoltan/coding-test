<?php

namespace App\Product\Infrastructure;

use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Model\Customers;
use App\Customer\Domain\Service\CustomerRepository;
use App\Customer\Infrastructure\CustomerMapper;
use App\Product\Domain\Exception\ProductDataException;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\Products;
use App\Product\Domain\Service\ProductRepository;
use App\Shared\Settings;
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
        return $products->findByIds($ids);
    }

    public function getById(string $id): Product
    {
        $products = $this->getProducts();
        $product = $products->findById($id);
        if (is_null($product)) {
            throw new ProductNotFoundException();
        }
        return $product;
    }

    /**
     * @return Products<Product>
     * @throws ProductDataException
     */
    public function getProducts(): Products
    {
        if (!$this->filesystem->exists(Settings::DATA_PATH . 'data/products.json')) {
            throw new ProductDataException();
        }
        $rawProductsData = $this->filesystem->readFile(Settings::DATA_PATH . 'data/products.json');
        if (!json_validate($rawProductsData)) {
            throw new ProductDataException();
        }
        $productsData = json_decode($rawProductsData, true);
        if (!is_array($productsData)) {
            throw new ProductDataException();
        }
        $products = new Products();
        foreach ($productsData as $productData) {
            $product = $this->productMapper->mapProductFromArray($productData);
            $products->append($product);
        }
        return $products;
    }
}
