<?php

namespace App\Customer\Application;

use App\Customer\Domain\Model\Customer;

readonly class CustomerMapper
{
    /**
     * @param Customer $customer
     * @return array{
     *     id:int,
     *     revenue:float
     * }
     */
    public function customerToOrderArray(Customer $customer): array
    {
        return [
            "id" => $customer->id,
            "revenue" => $customer->revenue
        ];
    }
}
