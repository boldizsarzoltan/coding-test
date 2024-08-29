<?php

namespace App\Customer\Application\Gateway;

use App\Customer\Application\CustomerMapper;
use App\Customer\Application\Exception\ExpectedRunTimeException;
use App\Customer\Application\Exception\UnexpectedRunTimeException;
use App\Customer\Domain\Exception\CustomerNotFoundException;
use App\Customer\Domain\Exception\CustomersDataException;
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
     *     revenue:float
     * }
     */
    public function getCustomerOrderData(int $customerId): array
    {
        try {
            return $this->customerMapper->customerToOrderArray(
                $this->customerRepository->getById($customerId)
            );
        } catch (CustomerNotFoundException $customerNotFoundException) {
            throw new \App\Customer\Application\Exception\CustomerNotFoundException(
                $customerNotFoundException->getMessage()
            );
        } catch (CustomersDataException $customersDataException) {
            throw new ExpectedRunTimeException($customersDataException->getMessage());
        } catch (\Throwable $unexpectedException) {
            throw new UnexpectedRunTimeException($unexpectedException->getMessage());
        }
    }
}
