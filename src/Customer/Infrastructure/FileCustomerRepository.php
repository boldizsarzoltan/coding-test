<?php

namespace App\Customer\Infrastructure;

use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Model\Customers;
use App\Customer\Domain\Service\CustomerRepository;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileCustomerRepository implements CustomerRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private CustomerMapper $customerMapper
    ) {
    }

    public function getById(int $id): ?Customer
    {
        return $this->getCustomers()->findById($id);
    }

    /**
     * @return void|Customers<Customer>
     */
    public function getCustomers(): ?Customers
    {
        if (!$this->filesystem->exists('data/customers.json')) {
            return null;
        }
        $rawCustomerDatas = $this->filesystem->readFile('data/customers.json');
        if (!json_validate($rawCustomerDatas)) {
            return null;
        }
        $customerDatas = json_decode($rawCustomerDatas, true);
        $customers = new Customers();
        foreach ($customerDatas as $customerData) {
            $customer = $this->customerMapper->mapCustomer($customerData);
            $customers->append($customer);
        }
        return $customers;
    }
}