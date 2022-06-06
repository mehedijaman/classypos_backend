<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Accounts\Expense;

use ClassyPOS\Models\Accounts\Expense\Expense;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    /**
     * Expense manipulation
     * Create, Update, Find Expense
     * return @void
     *
     * */

    public function listExpense()
    {
        return Expense::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return Expense::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ExpenseID)
    {
        // Recover soft deleted items back to list
        Expense::withTrashed()->find($ExpenseID)->restore();
    }

    public function clearTrash($ExpenseID)
    {
        // Permanently Delete
        Expense::withTrashed()->find($ExpenseID)->forceDelete();
    }
    

    public function showExpense(Expense $ExpenseID)
    {
        // View specific Expense @param Expense $ExpenseID.

        return response()->json($ExpenseID, 200);
    }

    public function storeExpense(Request $request)
    {
        $Expense = new Expense();
        $Expense->UserID        = $request->UserID;
        $Expense->CategoryID    = $request->CategoryID;
        $Expense->ShopID        = $request->ShopID;
        $Expense->FloorID       = $request->FloorID;
        $Expense->TerminalID    = $request->TerminalID;
        $Expense->Amount        = $request->Amount;
        $Expense->Account       = $request->Account;
        $Expense->ExpenseBy     = $request->ExpenseBy;
        $Expense->Notes         = $request->Notes;
        $Expense->Date          = $request->Date;

        $Expense->save();

        return response()->json($Expense, 201);
    }

    public function updateExpense(Request $request, Expense $ExpenseID)
    {
        $ExpenseID->update($request->all());

        return response()->json($ExpenseID, 200);
    }

    public function destroyExpense(Expense $ExpenseID)
    {
        $ExpenseID->delete();

        return response()->json(null, 204);
    }

    public function filterExpenseList($CategoryID=0, $ShopID=0, $FloorID=0, $TerminalID=0, $FromDate=0, $ToDate=0)
    {
        $Expenses = new Expense();
        $FilteredExpenseList = $Expenses->filterExpenseList($CategoryID, $ShopID, $FloorID, $TerminalID, $FromDate, $ToDate);

        return $FilteredExpenseList;
    }
}
