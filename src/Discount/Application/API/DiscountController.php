<?php

namespace App\Discount\Application\API;

use App\Discount\Application\Service\DiscountOrderMapper;
use App\Discount\Application\Service\DiscountService;
use App\Discount\Domain\Model\Order;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DiscountController
{
    public function __construct(
            private DiscountService $discountService,
            private DiscountOrderMapper $discountOrderMapper
    ) {
    }

    #[Route(path:'/discounts', name: 'discounts', methods: ['POST'])]
    public function getOrderWithDiscounts(): JsonResponse
    {
        $order = new Order();
        return new JsonResponse(
            $this->discountOrderMapper->mapOrderWithDiscountToArray(
                $this->discountService->getOrderWithDiscount($order)
            )
        );
    }
}