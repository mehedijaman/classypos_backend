<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Accounts\Expense;

use ClassyPOS\Models\Accounts\Expense\ExpenseCategory;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ExpenseCategoryController extends Controller
{
    /**
     * ExpenseCategory manipulation
     * Create, Update, Find ExpenseCategory
     * return @void
     *
     * */

    public function listExpenseCategory()
    {
        return ExpenseCategory::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ExpenseCategory::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ExpenseCategoryID)
    {
        // Recover soft deleted items back to list
        ExpenseCategory::withTrashed()->find($ExpenseCategoryID)->restore();
    }

    public function clearTrash($ExpenseCategoryID)
    {
        // Permanently Delete
        ExpenseCategory::withTrashed()->find($ExpenseCategoryID)->forceDelete();
    }
    
    public function showExpenseCategory(ExpenseCategory $ExpenseCategoryID)
    {
        // View specific ExpenseCategory @param ExpenseCategory $ExpenseCategoryID.

        return response()->json($ExpenseCategoryID, 200);
    }

    public function storeExpenseCategory(Request $request)
    {
        $ExpenseCategoryID = ExpenseCategory::create($request->all());

        return response()->json($ExpenseCategoryID, 201);
    }

    public function updateExpenseCategory(Request $request, ExpenseCategory $ExpenseCategoryID)
    {
        $ExpenseCategoryID->update($request->all());

        return response()->json($ExpenseCategoryID, 200);
    }

    public function destroyExpenseCategory(ExpenseCategory $ExpenseCategoryID)
    {
        $ExpenseCategoryID->delete();

        return response()->json(null, 204);
    }
}
