<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Shops;

use ClassyPOS\Models\Shops\ShopCashDrawers;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ShopCashDrawerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listShopCashDrawer()
    {
        // List of all ShopCashDrawer
        return ShopCashDrawers::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ShopCashDrawers::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ShopCashDrawerID)
    {
        // Recover soft deleted items back to list
        ShopCashDrawers::withTrashed()->find($ShopCashDrawerID)->restore();
    }

    public function clearTrash($ShopCashDrawerID)
    {
        // Permanently Delete
        ShopCashDrawers::withTrashed()->find($ShopCashDrawerID)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeShopCashDrawer(Request $request)
    {
        $ShopCashDrawer = ShopCashDrawers::create($request->all());

        return response()->json($ShopCashDrawer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $ShopCashDrawerID
     * @return \Illuminate\Http\Response
     */
    public function showShopCashDrawer(ShopCashDrawers $ShopCashDrawerID)
    {
        // Details of the given $ShopCashDrawerID
        return response()->json($ShopCashDrawerID, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ShopCashDrawerID
     * @return \Illuminate\Http\Response
     */
    public function updateShopCashDrawer(Request $request, $ShopCashDrawerID)
    {
        // Update the given $ShopCashDrawerID
        $ShopCashDrawerID->update($request->all());

        return response()->json($ShopCashDrawerID, 200);
    }

    public function daySummery(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $ShopCashDrawerID
     * @return \Illuminate\Http\Response
     */
    public function destroyShopCashDrawer(ShopCashDrawers $ShopCashDrawerID)
    {
        // Soft Delete the given $ShopCashDrawerID
        $ShopCashDrawerID->delete();

        return response()->json(null, 204);
    }
}
