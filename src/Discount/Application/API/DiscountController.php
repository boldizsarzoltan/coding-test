<?php

namespace App\Discount\Application\API;

use App\Discount\Application\OrderMapper;
use App\Discount\Application\Service\DiscountOrderMapper;
use App\Discount\Domain\Service\DiscountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DiscountController
{
    public function __construct(
        private DiscountService $discountService,
        private DiscountOrderMapper $discountOrderMapper,
        private OrderMapper $orderMapper,
    ) {
    }

    #[Route(path:'/discounts', name: 'discounts', methods: ['POST'])]
    public function getOrderWithDiscounts(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $order = $this->orderMapper->mapToOrder($parameters);
        if (is_null($order)) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
        $orderWithDiscount = $this->discountService->getDiscountedOrder($order);
        if (is_null($orderWithDiscount)) {
            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(
            $this->discountOrderMapper->mapOrderWithDiscountToArray(
                $orderWithDiscount
            )
        );
    }
}
