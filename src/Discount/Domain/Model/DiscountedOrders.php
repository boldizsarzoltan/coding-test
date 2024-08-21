<?php

namespace App\Discount\Domain\Model;

use App\Shared\TypedArray;

/**
 * @extends  TypedArray<DiscountedOrders>
 */
class DiscountedOrders extends TypedArray
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getType(): string
    {
        return DiscountedOrder::class;
    }

    //TODO: add errors to these
    public function offsetSet(mixed $key, mixed $value): void
    {
    }

    public function exchangeArray(object|array $array): array
    {
    }
}
