<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Taxes\Taxes;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class TaxController extends Controller
{
    /**
     * Taxes manipulation
     * Create, Update, Find Taxes
     * return @void
     *
     * */

    public function listTax()
    {
        return Taxes::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return Taxes::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($TaxID)
    {
        // Recover soft deleted items back to list
        Taxes::withTrashed()->find($TaxID)->restore();
    }

    public function clearTrash($TaxID)
    {
        // Permanently Delete
        Taxes::withTrashed()->find($TaxID)->forceDelete();
    }


    public function showTax(Taxes $TaxID)
    {
        // View specific Taxes @param Taxes $TaxID.

        return response()->json($TaxID, 200);
    }

    public function storeTax(Request $request)
    {
        $TaxID = Taxes::create($request->all());

        return response()->json($TaxID, 201);
    }

    public function updateTax(Request $request, Taxes $TaxID)
    {
        $TaxID->update($request->all());

        return response()->json($TaxID, 200);
    }

    public function destroyTax(Taxes $TaxID)
    {
        $TaxID->delete();

        return response()->json(null, 204);
    }
}
