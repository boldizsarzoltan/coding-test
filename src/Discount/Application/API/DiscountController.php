<?php

namespace App\Discount\Application\API;

use App\Discount\Application\Exception\MappingException;
use App\Discount\Application\OrderMapper;
use App\Discount\Application\Service\DiscountOrderMapper;
use App\Discount\Domain\Exception\DiscountOrderDataException;
use App\Discount\Domain\Exception\DiscountOrderException;
use App\Discount\Domain\Service\DiscountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

class DiscountController extends AbstractController
{
    public function __construct(
        private readonly DiscountService $discountService,
        private readonly DiscountOrderMapper $discountOrderMapper,
        private readonly OrderMapper $orderMapper,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route(path:'/discountedOrder', name: 'discounts', methods: ['POST'])]
    public function getOrderWithDiscounts(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        try {
            $order = $this->orderMapper->mapToOrder($parameters);
        } catch (MappingException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return new JsonResponse(
                [
                    'message' => 'Unknown exception'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        try {
            $orderWithDiscount = $this->discountService->getDiscountedOrder($order);
        } catch (DiscountOrderDataException $discountOrderException) {
            return new JsonResponse(
                [
                    'message' => $discountOrderException->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (DiscountOrderException $discountOrderException) {
            $this->logger->error($discountOrderException->getMessage());
            return new JsonResponse(
                [
                    'message' => 'Server had an unexpected internal error'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return new JsonResponse(['message' => 'Unknown error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(
            $this->discountOrderMapper->mapOrderWithDiscountToArray(
                $orderWithDiscount
            )
        );
    }
}
