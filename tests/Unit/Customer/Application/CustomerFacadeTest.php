<?php

namespace App\Tests\Unit\Customer\Application;

use App\Customer\Application\CustomerMapper;
use App\Customer\Application\Exception\ExpectedRunTimeException;
use App\Customer\Application\Exception\UnexpectedRunTimeException;
use App\Customer\Application\Gateway\CustomerFacade;
use App\Customer\Domain\Exception\CustomerNotFoundException;
use App\Customer\Domain\Exception\CustomersDataException;
use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Service\CustomerRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class CustomerFacadeTest extends TestCase
{

    private CustomerRepository&MockObject $customerRepository;
    private CustomerMapper&MockObject $customerMapper;
    private CustomerFacade $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = $this->createMock(CustomerRepository::class);
        $this->customerMapper = $this->createMock(CustomerMapper::class);
        $this->instance = new CustomerFacade(
            $this->customerRepository,
            $this->customerMapper
        );
    }

    public function testCustomerNotFoundExceptionFromRepository()
    {
        $customerId = 2;
        $this->expectException(\App\Customer\Application\Exception\CustomerNotFoundException::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willThrowException(new CustomerNotFoundException());
        $this->customerMapper
            ->expects(self::never())
            ->method('customerToOrderArray');
        $this->instance->getCustomerOrderData($customerId);
    }

    public function testCustomerNotFoundExceptionFromMapper()
    {
        $customerId = 2;
        $customer = $this->createMock(Customer::class);
        $this->expectException(\App\Customer\Application\Exception\CustomerNotFoundException::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->customerMapper
            ->expects(self::once())
            ->method('customerToOrderArray')
            ->with($customer)
            ->willThrowException(new CustomerNotFoundException());
        $this->instance->getCustomerOrderData($customerId);
    }

    public function testCustomersDataExceptionFromRepository()
    {
        $customerId = 2;
        $this->expectException(ExpectedRunTimeException::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willThrowException(new CustomersDataException());
        $this->customerMapper
            ->expects(self::never())
            ->method('customerToOrderArray');
        $this->instance->getCustomerOrderData($customerId);
    }

    public function testCustomersDataExceptionFromMapper()
    {
        $customerId = 2;
        $customer = $this->createMock(Customer::class);
        $this->expectException(ExpectedRunTimeException::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->customerMapper
            ->expects(self::once())
            ->method('customerToOrderArray')
            ->with($customer)
            ->willThrowException(new CustomersDataException());
        $this->instance->getCustomerOrderData($customerId);
    }

    public function testGeneralExceptionFromRepository()
    {
        $customerId = 2;
        $this->expectException(UnexpectedRunTimeException::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willThrowException(new \Exception());
        $this->customerMapper
            ->expects(self::never())
            ->method('customerToOrderArray');
        $this->instance->getCustomerOrderData($customerId);
    }

    public function testGeneralFromMapper()
    {
        $customerId = 2;
        $customer = $this->createMock(Customer::class);
        $this->expectException(UnexpectedRunTimeException::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->customerMapper
            ->expects(self::once())
            ->method('customerToOrderArray')
            ->with($customer)
            ->willThrowException(new \Exception());
        $this->instance->getCustomerOrderData($customerId);
    }

    public function testWorksCorrectly()
    {
        $customerId = 2;
        $result = ["result"];
        $customer = $this->createMock(Customer::class);
        $this->customerRepository
            ->expects(self::once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->customerMapper
            ->expects(self::once())
            ->method('customerToOrderArray')
            ->with($customer)
            ->willReturn($result);
        $this->assertEquals(
            $result,
            $this->instance->getCustomerOrderData($customerId)
        );
    }
}