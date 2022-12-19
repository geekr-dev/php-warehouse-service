<?php

namespace App\Service;

use Ecommerce\Common\DTOs\Warehouse\InventoryData;
use Ecommerce\Common\Events\Warehouse\InventoryUpdatedEvent;
use Ecommerce\Common\Services\RedisService as BaseRedisService;

class RedisService extends BaseRedisService
{
    public function getServiceName(): string
    {
        return 'warehouse';
    }

    public function publishInventoryUpdated(InventoryData $data): void
    {
        $this->publish(new InventoryUpdatedEvent($data));
    }
}
