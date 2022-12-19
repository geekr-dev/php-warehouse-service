<?php

namespace App\Actions;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Service\RedisService;
use Ecommerce\Common\DTOs\Warehouse\InventoryData;

class CreateInventoryAction
{
    public function __construct(
        private readonly RedisService $redisService,
    ) {
    }

    public function execute(Product $product, Warehouse $warehouse, int $quantity): InventoryData
    {
        Inventory::create([
            'productId' => $product->id,
            'warehouseId' => $warehouse->id,
            'quantity' => $quantity,
        ]);

        $totalQuantity = Inventory::totalQuantity($product);
        $inventoryData = new InventoryData($product->uuid, $totalQuantity);

        $this->redisService->publishInventoryUpdated($inventoryData);

        return $inventoryData;
    }
}
