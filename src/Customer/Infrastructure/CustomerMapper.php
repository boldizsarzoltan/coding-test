<?php

namespace App\Customer\Infrastructure;

use App\Customer\Domain\Exception\InvalidCustomerException;
use App\Customer\Domain\Model\Customer;

readonly class CustomerMapper
{
    /**
     * @param array<mixed> $data
     * @return Customer
     */
    public function mapCustomer(array $data): Customer
    {
        if(
            !isset($data["id"]) ||
            !isset($data["name"]) ||
            !isset($data["since"])
        ) {
            throw new InvalidCustomerException();
        }

        return new Customer(
            $data["id"],
            $data["name"],
            $data["since"],
            $data["revenue"] ?? 0
        );
    }
}