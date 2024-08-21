<?php

namespace App\Discount\Domain\Discount\Model;

use App\Shared\TypedArray;

/**
 * @extends  TypedArray<Discount>
 */
class Discounts extends TypedArray
{
    protected function getType(): string
    {
        return Discount::class;
    }
}
