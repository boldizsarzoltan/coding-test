<?php

namespace App\Tests\Unit\Customer\Application;

use App\Customer\Application\CustomerMapper;
use App\Customer\Domain\Exception\InvalidCustomerException;
use App\Customer\Domain\Model\Customer;
use App\Shared\Settings;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CustomerMapperTest extends TestCase
{

    private CustomerMapper $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new CustomerMapper();
    }

    public function testWorksCorrectly()
    {
        $id = 1;
        $revenue = 100;
        $customer = new Customer(
            $id,
            "name",
            new \DateTimeImmutable(""),
            $revenue
        );
        $this->assertEquals(
            [
                "id" => $id,
                "revenue" => $revenue
            ],
            $this->instance->customerToOrderArray($customer)
        );
    }
}