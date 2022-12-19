<?php

namespace App\Actions;

use App\Exceptions\ProductInventoryExceededException;
use App\Models\Inventory;
use App\Models\Product;
use App\Service\RedisService;
use Ecommerce\Common\DTOs\Order\OrderData;
use Ecommerce\Common\DTOs\Warehouse\InventoryData;

class DecreaseInventoryAction
{
    public function __construct(
        private readonly RedisService $redisService,
    ) {
    }

    public function execute(OrderData $data): void
    {
        $product = Product::findOrFail($data->productId);
        $this->decrease($product, $data->quantity);
        $totalQuantity = Inventory::totalQuantity($product);
        $inventoryData = new InventoryData(
            $product->uuid,
            $totalQuantity,
        );
        $this->redisService->publishInventoryUpdated($inventoryData);
    }

    private function decrease(Product $product, int $quantity): void
    {
        if (Inventory::totalQuantity($product) < $quantity) {
            throw new ProductInventoryExceededException(
                "There is not enough $product->name in inventory"
            );
        }
        $quantityLeft = $quantity;
        // 从多个仓库扣减库存
        foreach ($product->inventories as $inventory) {
            if ($inventory->quantity >= $quantityLeft) {
                $inventory->quantity -= $quantityLeft;
                $inventory->save();
                $this->deleteInventoryIfEmpty($inventory);
                break;
            }
            $quantityLeft -= $inventory->quantity;
            $inventory->delete();
        }
    }

    private function deleteInventoryIfEmpty(Inventory $inventory): void
    {
        if ($inventory->quantity === 0) {
            $inventory->delete();
        }
    }
}
