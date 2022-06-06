<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductRacks;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductRackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listProductRack()
    {
        // List of all ShopCashDrawer
        return ProductRacks::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductRacks::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ProductRackID)
    {
        // Recover soft deleted items back to list
        ProductRacks::withTrashed()->find($ProductRackID)->restore();
    }

    public function clearTrash($ProductRackID)
    {
        // Permanently Delete
        ProductRacks::withTrashed()->find($ProductRackID)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProductRack(Request $request)
    {
        $ProductRack = ProductRacks::create($request->all());

        return response()->json($ProductRack, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $ProductRackID
     * @return \Illuminate\Http\Response
     */
    public function showProductRack(ProductRacks $ProductRackID)
    {
        // Details of the given $ProductRackID
        return response()->json($ProductRackID, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ProductRackID
     * @return \Illuminate\Http\Response
     */
    public function updateProductRack(Request $request, $ProductRackID)
    {
        // Update the given $ProductRackID
        $ProductRackID->update($request->all());

        return response()->json($ProductRackID, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $ProductRackID
     * @return \Illuminate\Http\Response
     */
    public function destroyProductRack(ProductRacks $ProductRackID)
    {
        // Soft Delete the given $ProductRackID
        $ProductRackID->delete();

        return response()->json(null, 204);
    }
}
