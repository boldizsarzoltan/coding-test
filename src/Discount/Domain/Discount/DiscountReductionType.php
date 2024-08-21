<?php

namespace App\Discount\Domain\Discount;

enum DiscountReductionType: string
{
    case FreeCount = 'free_count';
    case TotalPercent = 'total_percent';
    case CheapestPercent = 'cheapest_percent';
}