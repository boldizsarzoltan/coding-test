<?php

namespace App\Customer\Domain\Model;

use App\Customer\Domain\Exception\InvalidCustomerException;
use App\Shared\Settings;

readonly class Customer
{
    public function __construct(
        public int $id,
        public string $name,
        public \DateTimeImmutable $since,
        public float $revenue = Settings::CUSTOMER_BASE_REVENUE
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->revenue < Settings::CUSTOMER_BASE_REVENUE) {
            throw new InvalidCustomerException();
        }
    }
}
