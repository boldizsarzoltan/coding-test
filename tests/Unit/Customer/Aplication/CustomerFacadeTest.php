<?php

namespace App\Tests\Unit\Customer\Aplication;

use App\Customer\Application\CustomerMapper;
use App\Customer\Application\Gateway\CustomerFacade;
use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Service\CustomerRepository;
use PHPUnit\Framework\TestCase;

class CustomerFacadeTest extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject&CustomerRepository $customerRepositoryMock;
    private \PHPUnit\Framework\MockObject\MockObject&CustomerMapper $customerMapperMock;
    private CustomerFacade $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customerRepositoryMock = $this->createMock(CustomerRepository::class);
        $this->customerMapperMock = $this->createMock(CustomerMapper::class);
        $this->instance = new CustomerFacade(
            $this->customerRepositoryMock,
            $this->customerMapperMock
        );
    }

    public function testThatItWorks(): void
    {
        $customerId = 1;
        $customerMock = $this->createMock(Customer::class);
        $this->customerRepositoryMock
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customerMock);
        $array = ["array"];
        $this->customerMapperMock
            ->expects(self::atLeastOnce())
            ->method("customerToOrderArray")
            ->with($customerMock)
            ->willReturn($array);
        $this->assertEquals($array, $this->instance->getCustomerOrderData($customerId));
    }
}