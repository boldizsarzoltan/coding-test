<?php

namespace App\Tests\Unit\Customer\Infrastructure;

use App\Customer\Domain\Exception\InvalidCustomerException;
use App\Customer\Domain\Model\Customer;
use App\Customer\Infrastructure\CustomerMapper;
use App\Shared\Settings;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CustomerMapperTest extends TestCase
{
    private const string DATE = "2024-07-31 00:00:00";
    private CustomerMapper $instance;


    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new CustomerMapper();
    }

    #[DataProvider('invalidDataProvider')]
    public function testThrowsExceptionForInvalidData(array $data): array
    {
        $this->expectException(InvalidCustomerException::class);
        $this->instance->mapCustomer($data);
    }

    #[DataProvider('validDataProvider')]
    public function testMapperWorksCorrectly(array $data): void
    {
        $this->assertEquals(
            new Customer(
                $data['id'],
                $data['name'],
                new \DateTimeImmutable($data['since']),
                $data["revenue"] ?? Settings::CUSTOMER_BASE_REVENUE
            ),
            $this->instance->mapCustomer($data)
        );
    }

    public static function invalidDataProvider(): array
    {
        return [
            "id not set" => [[]],
            "id null" => [["id" => null]],
            "id array" => [["id" => []]],
            "since not set" => [["id" => 1]],
            "since null" => [["id" => 1, "since" => null]],
            "since array" => [["id" => 1, "since" => []]],
            "name not set" => [["id" => 1, "since" => ""]],
            "name null" => [["id" => 1, "since" => "", "name" => null]],
            "name array" => [["id" => 1, "since" => "", "name" => []]],
            "revenue string" => [["id" => 1, "since" => "", "name" => "", "revenue" => "asdasd"]],
        ];
    }


    public static function validDataProvider()
    {
        return [
            "without revenue" => [["id" => 1, "since" => self::DATE, "name" => ""]],
            "with revenue" => [["id" => 1, "since" => self::DATE, "name" => "", "revenue" => "10"]],
        ];
    }
}