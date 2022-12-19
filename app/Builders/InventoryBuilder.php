<?php

namespace App\Builders;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class InventoryBuilder extends Builder
{
    public function totalQuantity(Product $product): int
    {
        return Inventory::select('quantity')
            ->where('product_id', $product->id)
            ->sum('quantity');
    }
}
