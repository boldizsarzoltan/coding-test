<?php

namespace App\Customer\Domain\Model;

class Customer
{
    public function __construct(
      public int $id,
      public string $name,
      public \DateTimeImmutable $since,
      public int $revenue = 0
    ) {
    }
}