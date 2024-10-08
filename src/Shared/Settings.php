<?php

namespace App\Shared;

class Settings
{
    public const int MAX_DISCOUNT_PERCENT = 90;
    public const int MAX_FREE_COUNT = 5;
    public const int DEFAULT_FREE_COUNT = 0;
    public const int PRICE_VALUE_MULTIPLIER = 100;
    public const float MIN_PRICE = 0.00;
    public const float CUSTOMER_BASE_REVENUE = 0.00;
    public const string DATA_PATH = __DIR__ . DIRECTORY_SEPARATOR
    . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
}
