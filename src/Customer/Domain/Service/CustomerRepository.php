<?php

namespace App\Customer\Domain\Service;

use App\Customer\Domain\Model\Customer;

interface CustomerRepository
{
    public function getById(int $id): ?Customer;
}
