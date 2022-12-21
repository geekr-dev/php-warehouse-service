<?php

namespace App\Builders;

use App\Models\Inventory;
use App\Models\Product;
use Ecommerce\Common\DTOs\Warehouse\InventoryData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class InventoryBuilder extends Builder
{
    public function totalQuantity(Product $product): int
    {
        return Inventory::select('quantity')
            ->where('product_id', $product->id)
            ->sum('quantity');
    }

    public function totalQuantities(Collection $products)
    {
        return $products
            ->mapWithKeys(fn (Product $product) => [
                $product->id => self::totalQuantity($product),
            ])
            ->map(fn (int $quantity, int $productId) => new InventoryData($productId, $quantity))
            ->values();
    }
}
