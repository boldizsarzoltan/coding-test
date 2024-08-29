<?php

namespace App\Discount\Application;

use App\Discount\Application\Exception\MappingException;
use App\Discount\Application\Exception\MappingItemException;
use App\Discount\Domain\Order\Model\Order;
use App\Discount\Domain\Order\Model\OrderItem;
use App\Discount\Domain\Order\Model\OrderItems;
use App\Shared\Settings;
use Symfony\Component\HttpFoundation\Request;

class OrderMapper
{
    /**
     * @return Order
     * @throws MappingException
     */
    public function mapToOrder(mixed $data): Order
    {
        if (!is_array($data)) {
            throw new MappingException("request data invalid");
        }
        if (!isset($data["id"])) {
            throw new MappingException("order id not set");
        }
        if (!is_int($data["id"]) && !ctype_digit($data['id'])) {
            throw new MappingException("order id is not integer");
        }
        if (!isset($data["customer-id"])) {
            throw new MappingException("customer id not set");
        }
        if (!is_int($data["customer-id"]) && !ctype_digit($data['customer-id'])) {
            throw new MappingException("customer id is not integer");
        }
        if (!isset($data["total"])) {
            throw new MappingException("total not set");
        }
        if (!is_int($data["total"]) && !ctype_digit($data['total'])) {
            throw new MappingException("total is not integer");
        }
        if (!isset($data["items"])) {
            throw new MappingException("items not set");
        }
        if (!is_array($data["items"])) {
            throw new MappingException("items are not array");
        }
        [$errors, $orderItems] = $this->mapItems($data["items"]);
        if (!empty($errors)) {
            throw new MappingException("items have errors:" . json_encode($errors));
        }
        return new Order(
            $data["id"],
            $data["customer-id"],
            $orderItems,
            $data["total"] * Settings::PRICE_VALUE_MULTIPLIER,
        );
    }

    /**
     * @param array<mixed> $items
     * @return array{0:array<string[]>, 1:OrderItems<OrderItem>}
     */
    public function mapItems(array $items): array
    {
        $errors = [];
        $orderItems = new OrderItems();
        foreach ($items as $key => $item) {
            try {
                [$errorsFromMapper, $item] = $this->mapItem($item);
                $errors[$key] = $errorsFromMapper;
                if (is_null($item)) {
                    continue;
                }
                $orderItems->append($item);
            } catch (MappingItemException $e) {
                $errors[$key] = [$e->getMessage()];
            }
        }
        return [$errors, $orderItems];
    }

    /**
     * @return array{array<string>,?OrderItem}
     * @throws MappingItemException
     */
    private function mapItem(mixed $item): array
    {
        if (!is_array($item)) {
            throw new MappingItemException("item must be an array");
        }
        $errors = [];
        $productIdSet = isset($item["product-id"]);
        if (!$productIdSet) {
            $errors[] = "product-id not set";
        }
        if ($productIdSet && !is_string($item["product-id"])) {
            $errors[] = "product-id not string";
        }
        $quantityIsSet = !isset($item["quantity"]);
        if (!$quantityIsSet) {
            $errors[] = "quantity not set";
        }
        if ($quantityIsSet && !is_int($item["quantity"]) && !ctype_digit($item['quantity'])) {
            $errors[] = "quantity not int";
        }
        $unitPriceIsSet = $item["unit-price"];
        if (!isset($unitPriceIsSet)) {
            $errors[] = "unit-price not set";
        }
        if ($productIdSet && !is_float($item["unit-price"]) && is_numeric($item["unit-price"])) {
            $errors[] = "unit-price not float";
        }
        if (!isset($item["total"]) && !is_float($item["total"]) && is_numeric($item["total"])) {
            $errors[] = "total not set";
        }
        if (!empty($errors)) {
            return [$errors, null];
        }
        return [
            [],
            new OrderItem(
                $item["product-id"],
                $item["quantity"],
                $item["unit-price"]  * Settings::PRICE_VALUE_MULTIPLIER,
                $item["total"] * Settings::PRICE_VALUE_MULTIPLIER,
            )
        ];
    }
}
