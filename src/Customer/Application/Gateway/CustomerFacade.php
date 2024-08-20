<?php

namespace App\Customer\Application\Gateway;

use App\Customer\Application\CustomerMapper;
use App\Customer\Domain\Service\CustomerRepository;

readonly class CustomerFacade
{
    public function __construct(
      private CustomerRepository $customerRepository,
      private CustomerMapper $customerMapper
    ) {
    }

    /**
     * @param int $customerId
     * @return array{
     *     id:int,
     *     revenue:int
     * }|null
     */
    public function getCustomerOrderData(int $customerId): ?array
    {
        $customer = $this->customerRepository->getById($customerId);
        if(is_null($customer)) {
            return null;
        }
        return $this->customerMapper->customerToOrderArray($customer);
    }
}