<?php

namespace App\Discount\Domain\Model;

use App\Shared\TypedArray;

/**
 * @extends  TypedArray<DiscountedOrder>
 */
class DiscountedOrdersItems extends TypedArray
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getType(): string
    {
        return DiscountedOrderItems::class;
    }

    //TODO: add errors to these
    public function offsetSet(mixed $key, mixed $value): void
    {
    }

    public function exchangeArray(object|array $array): array
    {
        return $this->getArrayCopy();
    }
}
