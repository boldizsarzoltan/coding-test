<?php

namespace App\Discount\Domain\Discount\Model;

enum DiscountReductionType: string
{
    case FreeCount = 'free_count';
    case TotalPercent = 'total_percent';
    case CheapestPercent = 'cheapest_percent';
}
