<?php

namespace App\Customer\Infrastructure;

use App\Customer\Domain\Exception\InvalidCustomerException;
use App\Customer\Domain\Model\Customer;
use App\Shared\Settings;

readonly class CustomerMapper
{
    /**
     * @param array<mixed> $data
     * @return Customer
     */
    public function mapCustomer(array $data): Customer
    {
        if (
            !isset($data["id"]) ||
            (!is_integer($data["id"]) && !ctype_digit($data["id"]))
        ) {
            throw new InvalidCustomerException("customer id must be an integer");
        }
        if (
            !isset($data["since"]) ||
            !is_string($data["since"])
        ) {
            throw new InvalidCustomerException("since must be a string");
        }

        if (
            !isset($data["name"]) ||
            !is_string($data["name"])
        ) {
            throw new InvalidCustomerException("name must be a string");
        }

        $revenue = $data["revenue"] ?? 0;
        return new Customer(
            (int) $data["id"],
            $data["name"],
            new \DateTimeImmutable($data["since"]),
            (float) $revenue
        );
    }
}
