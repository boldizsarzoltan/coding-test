<?php

namespace App\Discount\Domain\Model;

readonly class Customer
{
    public function __construct(
        public int $id,
        public int $total
    ) {
    }
}