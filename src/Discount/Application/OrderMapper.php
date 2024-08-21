<?php

namespace App\Discount\Application;

use App\Discount\Domain\Order\Order;
use Symfony\Component\HttpFoundation\Request;

class OrderMapper
{
    public function mapRequestToOrder(Request $request): ?Order
    {
        $parameters = json_decode($request->getContent(), true);
        if(!isset()) {

        }
    }
}