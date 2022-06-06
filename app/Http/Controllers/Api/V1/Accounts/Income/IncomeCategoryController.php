<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Accounts\Income;

use ClassyPOS\Models\Accounts\Income\IncomeCategory;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class IncomeCategoryController extends Controller
{
    /**
     * IncomeCategory manipulation
     * Create, Update, Find IncomeCategory
     * return @void
     *
     * */

    public function listIncomeCategory()
    {
        return IncomeCategory::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return IncomeCategory::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($IncomeCategoryID)
    {
        // Recover soft deleted items back to list
        IncomeCategory::withTrashed()->find($IncomeCategoryID)->restore();
    }

    public function clearTrash($IncomeCategoryID)
    {
        // Permanently Delete
        IncomeCategory::withTrashed()->find($IncomeCategoryID)->forceDelete();
    }

    public function showIncomeCategory(IncomeCategory $IncomeCategoryID)
    {
        // View specific IncomeCategory @param IncomeCategory $IncomeCategoryID.

        return response()->json($IncomeCategoryID, 200);
    }

    public function storeIncomeCategory(Request $request)
    {
        $IncomeCategoryID = IncomeCategory::create($request->all());

        return response()->json($IncomeCategoryID, 201);
    }

    public function updateIncomeCategory(Request $request, IncomeCategory $IncomeCategoryID)
    {
        $IncomeCategoryID->update($request->all());

        return response()->json($IncomeCategoryID, 200);
    }

    public function destroyIncomeCategory(IncomeCategory $IncomeCategoryID)
    {
        $IncomeCategoryID->delete();

        return response()->json(null, 204);
    }
}
