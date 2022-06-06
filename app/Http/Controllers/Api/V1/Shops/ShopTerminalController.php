<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Shops;

use ClassyPOS\Models\Shops\ShopTerminals;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ShopTerminalController extends Controller
{
    /**
     * ShopTerminals manipulation
     * Create, Update, Find ShopTerminals
     * return @void
     *
     * */

    public function listShopTerminal()
    {
        return ShopTerminals::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ShopTerminals::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ShopTerminalID)
    {
        // Recover soft deleted items back to list
        ShopTerminals::withTrashed()->find($ShopTerminalID)->restore();
    }

    public function clearTrash($ShopTerminalID)
    {
        // Permanently Delete
        ShopTerminals::withTrashed()->find($ShopTerminalID)->forceDelete();
    }


    public function showShopTerminal(ShopTerminals $ShopTerminalID)
    {
        // View specific ShopTerminals @param ShopTerminals $ShopTerminalID.

        return response()->json($ShopTerminalID, 200);
    }

    public function storeShopTerminal(Request $request)
    {
        $ShopTerminalID = ShopTerminals::create($request->all());

        return response()->json($ShopTerminalID, 201);
    }

    public function updateShopTerminal(Request $request, ShopTerminals $ShopTerminalID)
    {
        $ShopTerminalID->update($request->all());

        return response()->json($ShopTerminalID, 200);
    }

    public function destroyShopTerminal(ShopTerminals $ShopTerminalID)
    {
        $ShopTerminalID->delete();

        return response()->json(null, 204);
    }
}
