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
            !is_integer($data["id"]) ||
            !isset($data["name"]) ||
            !is_string($data["name"]) ||
            !isset($data["since"]) ||
            !is_string($data["since"])
        ) {
            throw new InvalidCustomerException();
        }

        $revenue = $data["revenue"] ?? 0;
        return new Customer(
            $data["id"],
            $data["name"],
            new \DateTimeImmutable($data["since"]),
            $revenue * Settings::PRICE_VALUE_MULTIPLIER
        );
    }
}
