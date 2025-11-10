<?php

use App\Models\Item;
use App\Models\WholesalePrice;

if (!function_exists('indo_currency')) {
    function indo_currency($number, $withRp = true)
    {
        $result = number_format($number, 0, ',', '.');
        if ($withRp) {
            return 'Rp ' . $result;
        }
        return $result;
    }
}

if (!function_exists('calculate_price')) {
    function calculate_price(Item $item, $quantity)
    {
        $wholesalePrice = WholesalePrice::where('item_id', $item->id)
            ->where('min_qty', '<=', $quantity)
            ->orderBy('min_qty', 'desc')
            ->first();

        if ($wholesalePrice) {
            return $wholesalePrice->price;
        }

        return $item->selling_price;
    }
}
