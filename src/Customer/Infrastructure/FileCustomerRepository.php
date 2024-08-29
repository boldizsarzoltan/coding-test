<?php

namespace App\Customer\Infrastructure;

use App\Customer\Domain\Exception\CustomerNotFoundException;
use App\Customer\Domain\Exception\CustomersDataException;
use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Model\Customers;
use App\Customer\Domain\Service\CustomerRepository;
use App\Shared\Settings;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileCustomerRepository implements CustomerRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private CustomerMapper $customerMapper
    ) {
    }

    public function getById(int $id): Customer
    {
        $customers = $this->getCustomers();
        if (is_null($customers)) {
            throw new CustomersDataException();
        }
        $customer = $customers->findById($id);
        if (is_null($customer)) {
            throw new CustomerNotFoundException();
        }
        return $customer;
    }

    /**
     * @return null|Customers<Customer>
     */
    public function getCustomers(): ?Customers
    {
        if (!$this->filesystem->exists(Settings::DATA_PATH . 'data/customers.json')) {
            return null;
        }
        $rawCustomersData = $this->filesystem->readFile(Settings::DATA_PATH . 'data/customers.json');
        if (!json_validate($rawCustomersData)) {
            return null;
        }
        $customersData = json_decode($rawCustomersData, true);
        if (!is_array($customersData)) {
            return null;
        }
        $customers = new Customers();
        foreach ($customersData as $customerData) {
            $customer = $this->customerMapper->mapCustomer($customerData);
            $customers->append($customer);
        }
        return $customers;
    }
}
