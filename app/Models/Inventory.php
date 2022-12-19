<?php

namespace App\Models;

use App\Builders\InventoryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public function newEloquentBuilder($query)
    {
        return new InventoryBuilder($query);
    }
}
