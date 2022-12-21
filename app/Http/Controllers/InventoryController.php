<?php

namespace App\Http\Controllers;

use App\Actions\CreateInventoryAction;
use App\Http\Requests\GetInventoryRequest;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Resources\ProductResource;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetInventoryRequest $request)
    {
        $products = Product::find($request->getProductIds());
        $inventories = Inventory::totalQuantities($products);

        return [
            'data' => $inventories,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreInventoryRequest  $request
     * @param \App\Actions\CreateInventoryAction $action
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInventoryRequest $request, CreateInventoryAction $action)
    {
        $inventoryData = $action->execute(
            Product::where('uuid', $request->getProductId())->first(),
            Warehouse::where('uuid', $request->getWarehouseId())->first(),
            $request->getQuantity(),
        );
        return response([
            'data' => $inventoryData
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function get(Product $product)
    {
        return [
            'data' => (new ProductResource($product)),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
