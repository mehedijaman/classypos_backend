<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductWarehouses;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listProductWarehouse()
    {
        // List of all ShopCashDrawer
        return ProductWarehouses::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductWarehouses::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ProductWarehouseID)
    {
        // Recover soft deleted items back to list
        ProductWarehouses::withTrashed()->find($ProductWarehouseID)->restore();
    }

    public function clearTrash($ProductWarehouseID)
    {
        // Permanently Delete
        ProductWarehouses::withTrashed()->find($ProductWarehouseID)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProductWarehouse(Request $request)
    {
        $ProductWarehouse = ProductWarehouses::create($request->all());

        return response()->json($ProductWarehouse, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $ProductWarehouseID
     * @return \Illuminate\Http\Response
     */
    public function showProductWarehouse(ProductWarehouses $ProductWarehouseID)
    {
        // Details of the given $ProductWarehouseID
        return response()->json($ProductWarehouseID, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ProductWarehouseID
     * @return \Illuminate\Http\Response
     */
    public function updateProductWarehouse(Request $request, $ProductWarehouseID)
    {
        // Update the given $ProductWarehouseID
        $ProductWarehouseID->update($request->all());

        return response()->json($ProductWarehouseID, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $ProductWarehouseID
     * @return \Illuminate\Http\Response
     */
    public function destroyProductWarehouse(ProductWarehouses $ProductWarehouseID)
    {
        // Soft Delete the given $ProductWarehouseID
        $ProductWarehouseID->delete();

        return response()->json(null, 204);
    }
}
