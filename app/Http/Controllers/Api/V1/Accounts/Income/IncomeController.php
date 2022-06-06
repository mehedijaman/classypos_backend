<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Accounts\Income;

use ClassyPOS\Models\Accounts\Income\Income;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class IncomeController extends Controller
{
    /**
     * Income manipulation
     * Create, Update, Find Income
     * return @void
     *
     * */

    public function listIncome()
    {
        return Income::paginate(5);
    }


    public function listTrash()
    {
        // view only trashed items
        return Income::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($IncomeID)
    {
        // Recover soft deleted items back to list
        Income::withTrashed()->find($IncomeID)->restore();
    }

    public function clearTrash($IncomeID)
    {
        // Permanently Delete
        Income::withTrashed()->find($IncomeID)->forceDelete();
    }


    public function showIncome(Income $IncomeID)
    {
        // View specific Income @param Income $IncomeID.

        return response()->json($IncomeID, 200);
    }

    public function storeIncome(Request $request)
    {
        $Income = new Income();
        $Income->UserID     = $request->UserID;
        $Income->CategoryID = $request->CategoryID;
        $Income->ShopID     = $request->ShopID;
        $Income->TerminalID = $request->TerminalID;
        $Income->FloorID    = $request->FloorID;
        $Income->Account    = $request->Account;
        $Income->Amount     = $request->Amount;
        $Income->Notes      = $request->Notes;
        $Income->Date       = $request->Date;

        $Income->save();

        return response()->json($Income, 201);
    }

    public function updateIncome(Request $request, Income $IncomeID)
    {
        $IncomeID->update($request->all());

        return response()->json($IncomeID, 200);
    }

    public function destroyIncome(Income $IncomeID)
    {
        $IncomeID->delete();

        return response()->json(null, 204);
    }

    public function filterIncomeList($CategoryID=0, $ShopID=0, $FloorID=0, $TerminalID=0, $FromDate=0, $ToDate=0)
    {
        $Incomes = new Income();
        $FilteredIncomeList = $Incomes->filterIncomeList($CategoryID, $ShopID, $FloorID, $TerminalID, $FromDate, $ToDate);

        return $FilteredIncomeList;
    }
}
