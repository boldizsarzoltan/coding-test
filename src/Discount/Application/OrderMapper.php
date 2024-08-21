<?php

namespace App\Discount\Application;

use App\Discount\Domain\Order\Model\Order;
use Symfony\Component\HttpFoundation\Request;

class OrderMapper
{
    /**
     * @return Order|null
     */
    public function mapToOrder(mixed $data): ?Order
    {
        return null;
    }
}
