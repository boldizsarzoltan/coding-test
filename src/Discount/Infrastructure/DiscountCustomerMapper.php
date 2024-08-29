<?php

namespace App\Discount\Infrastructure;

use App\Discount\Domain\Exception\DiscountOrderDataMappingException;
use App\Discount\Domain\Model\Customer;
use App\Shared\Settings;

class DiscountCustomerMapper
{
    /**
     * @param array<mixed> $discountCustomer
     * @return Customer
     */
    public function mapArrayToDiscountCustomer(array $discountCustomer): Customer
    {
        if (!isset($discountCustomer["id"])) {
            throw new DiscountOrderDataMappingException("customer-id is required");
        }
        if (!isset($discountCustomer["revenue"])) {
            throw new DiscountOrderDataMappingException("revenue is required");
        }
        if (!is_int($discountCustomer["id"]) && !ctype_digit($discountCustomer['id'])) {
            throw new DiscountOrderDataMappingException();
        }
        if (!is_numeric($discountCustomer["revenue"])) {
            throw new DiscountOrderDataMappingException();
        }
        /** @var array{revenue:float, id:int} $discountCustomer */
        $total = (float)$discountCustomer["revenue"];
        return new Customer(
            (int) $discountCustomer["id"],
            (int) ($total * Settings::PRICE_VALUE_MULTIPLIER)
        );
    }
}
