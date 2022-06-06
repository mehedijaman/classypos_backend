<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Shops;

use ClassyPOS\Models\Shops\ShopFloors;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ShopFloorController extends Controller
{
    /**
     * ShopFloors manipulation
     * Create, Update, Find ShopFloors
     * return @void
     *
     * */

    public function listShopFloor()
    {
        return ShopFloors::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ShopFloors::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ShopFloorID)
    {
        // Recover soft deleted items back to list
        ShopFloors::withTrashed()->find($ShopFloorID)->restore();
    }

    public function clearTrash($ShopFloorID)
    {
        // Permanently Delete
        ShopFloors::withTrashed()->find($ShopFloorID)->forceDelete();
    }


    public function showShopFloor(ShopFloors $ShopFloorID)
    {
        // View specific ShopFloors @param ShopFloors $ShopFloorID.

        return response()->json($ShopFloorID, 200);
    }

    public function storeShopFloor(Request $request)
    {
        $ShopFloorID = ShopFloors::create($request->all());

        return response()->json($ShopFloorID, 201);
    }

    public function updateShopFloor(Request $request, ShopFloors $ShopFloorID)
    {
        $ShopFloorID->update($request->all());

        return response()->json($ShopFloorID, 200);
    }

    public function destroyShopFloor(ShopFloors $ShopFloorID)
    {
        $ShopFloorID->delete();

        return response()->json(null, 204);
    }
}
