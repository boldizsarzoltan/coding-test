<?php

namespace App\Customer\Domain\Model;

readonly class Customer
{
    public function __construct(
        public int $id,
        public string $name,
        public \DateTimeImmutable $since,
        public float $revenue = 0
    ) {
    }
}
