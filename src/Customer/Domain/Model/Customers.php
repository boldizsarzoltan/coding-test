<?php

namespace App\Customer\Domain\Model;

use App\Shared\TypedArray;
/**
 * @extends  TypedArray<Customer>
 */
class Customers extends TypedArray
{
    public function findById(int $idToFind): ?Customer
    {
        /** @var Customer $customer */
        foreach ($this->getArrayCopy() as $customer) {
            if($customer->id === $idToFind) {
                return $customer;
            }
        }
        return null;
    }

    protected function getType(): string
    {
        return Customer::class;
    }
}