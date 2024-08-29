<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Exception\DiscountOrderDataMappingException;
use App\Discount\Domain\Model\Customer;

class DiscountCustomerMapper
{
    /**
     * @param array<mixed> $discountCustomer
     * @return Customer
     */
    public function mapArrayToDiscountCustomer(array $discountCustomer): Customer
    {
        if (!isset($discountCustomer["id"]) || !$discountCustomer["total"]) {
            throw new DiscountOrderDataMappingException();
        }
        if (!is_int($discountCustomer["id"]) && !ctype_digit($discountCustomer['id'])) {
            throw new DiscountOrderDataMappingException();
        }
        if (!is_int($discountCustomer["total"]) && !ctype_digit($discountCustomer['total'])) {
            throw new DiscountOrderDataMappingException();
        }
        /** @var array{total:int, id:int} $discountCustomer */
        return new Customer(
            (int) $discountCustomer["id"],
            (int) $discountCustomer["total"]
        );
    }
}
