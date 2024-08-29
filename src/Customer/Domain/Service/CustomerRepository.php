<?php

namespace App\Customer\Domain\Service;

use App\Customer\Domain\Exception\CustomerNotFoundException;
use App\Customer\Domain\Exception\CustomersDataException;
use App\Customer\Domain\Model\Customer;

interface CustomerRepository
{
    /**
     * @throws CustomerNotFoundException
     * @throws CustomersDataException
     */
    public function getById(int $id): Customer;
}
