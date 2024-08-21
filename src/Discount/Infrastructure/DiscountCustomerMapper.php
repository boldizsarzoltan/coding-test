<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Model\Customer;

class DiscountCustomerMapper
{
    /**
     * @param array<int|string|null> $discountCustomer
     * @return Customer
     */
    public function mapArrayToDiscountCustomer(array $discountCustomer): ?Customer
    {
        if (!isset($discountCustomer["id"]) || !$discountCustomer["total"]) {
            return null;
        }
        return new Customer(
            (int) $discountCustomer["id"],
            (int) $discountCustomer["total"]
        );
    }
}
