<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Exception\DiscountOrderDataMappingException;
use App\Discount\Domain\Model\Customer;

class DiscountCustomerMapper
{
    /**
     * @param array<int|string|null> $discountCustomer
     * @return Customer
     */
    public function mapArrayToDiscountCustomer(array $discountCustomer): Customer
    {
        if (!isset($discountCustomer["id"]) || !$discountCustomer["total"]) {
            throw new DiscountOrderDataMappingException();
        }
        return new Customer(
            (int) $discountCustomer["id"],
            (int) $discountCustomer["total"]
        );
    }
}
