<?php

namespace App\Tests\Unit\Customer\Domain;

use App\Customer\Domain\Exception\InvalidCustomerException;
use App\Customer\Domain\Model\Customer;
use App\Customer\Infrastructure\CustomerMapper;
use App\Shared\Settings;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testThrowsExceptionIfRevenueToSmall()
    {
        $this->expectException(InvalidCustomerException::class);
        new Customer(
            1,
            "",
            new \DateTimeImmutable(""),
            -1
        );
    }
}
