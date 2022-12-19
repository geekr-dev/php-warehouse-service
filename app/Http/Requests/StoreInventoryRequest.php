<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'productId' => 'required|exists:products,uuid',
            'warehouseId' => 'required|exists:warehouses,uuid',
            'quantity' => 'required|numeric',
        ];
    }
}
