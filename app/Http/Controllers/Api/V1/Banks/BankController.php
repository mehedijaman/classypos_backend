<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Banks;

use ClassyPOS\Models\Banks\Bank;
use ClassyPOS\Models\Banks\BankBalance;
use ClassyPOS\Models\Banks\BankLedger;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class BankController extends Controller
{
    /**
     * Bank manipulation
     * Create, Update, Find Bank
     * return @void
     *
     * */

    public function listBank()
    {
        $Bank = BankBalance::select(
            'bank_balances.id',

            'bank_balances.BankID',
            'banks.Name',
            'banks.Address',
            'banks.OpeningBalance',
            'banks.AccountName',
            'banks.AccountNumber',
            'banks.IsDefault',
            'banks.Status',
            'bank_balances.Balance'
        )
            ->leftJoin('banks', 'banks.id', 'bank_balances.BankID')
            ->paginate(10);

        return response()->json($Bank, 200);
    }
    
    
    public function listTrash()
    {
        // view only trashed items
        return Bank::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($BankID)
    {
        // Recover soft deleted items back to list
        Bank::withTrashed()->find($BankID)->restore();
    }

    public function clearTrash($BankID)
    {
        // Permanently Delete
        Bank::withTrashed()->find($BankID)->forceDelete();
    }


    public function showBank(Bank $BankID)
    {
        // View specific Bank @param Bank $BankID.

        return response()->json($BankID, 200);
    }

    public function storeBank(Request $request)
    {
        $Bank = new Bank();
        $Bank->Name             = $request->Name;
        $Bank->Address          = $request->Address;
        $Bank->OpeningBalance   = $request->OpeningBalance;
        $Bank->AccountName      = $request->AccountName;
        $Bank->AccountNumber    = $request->AccountNumber;
        $Bank->IsDefault        = $request->IsDefault;
        $Bank->Status           = $request->Status;

        $Bank->save();

        $BankID = $Bank->id;

        $BankLedger = new BankLedger();
        $BankLedger->BankID  = $BankID;
        $BankLedger->UserID  = $request->UserID;
        $BankLedger->Deposit = $request->OpeningBalance;
        $BankLedger->Balance = $request->OpeningBalance;

        $BankLedger->save();

        $BankBalance = new BankBalance();
        $BankBalance->BankID    = $BankID;
        $BankBalance->Balance   = $request->OpeningBalance;

        $BankBalance->save();

        return response()->json($Bank, 201);
    }

    public function updateBank(Request $request, Bank $BankID)
    {
        $BankID->update($request->all());
        $Bank = $BankID->id;

        $BankBalance = BankBalance::where('BankID', $Bank)->first();
        $BankBalance->Balance   = $request->OpeningBalance;

        $BankBalance->save();

        return response()->json($BankID, 200);
    }

    public function bankTransaction(Request $request)
    {
        $BankTransaction = new BankLedger();
        $BankTransaction->transaction($request);

        return response()->json("Transaction: OK", 201);
    }

    public function destroyBank(Bank $BankID)
    {
        $BankID->delete();

        return response()->json(null, 204);
    }

    public function ledger($BankID=0, $UserID=0, $Status=0, $FromPaymentDate=0, $ToPaymentDate=0, $FromDate=0, $ToDate=0) {
        $Bank = BankLedger::where('BankID','=',$BankID)->leftjoin('banks','banks.id','=','bank_ledgers.BankID')->select('bank_ledgers.created_at','banks.Name','bank_ledgers.Balance','bank_ledgers.Deposit','bank_ledgers.Withdraw')->get();
        return $Bank;
        return BankLedger::all();
        $BankLedger = new BankLedger();
        $FilteredBankLedger = $BankLedger->filteredLedger($BankID, $UserID, $Status, $FromPaymentDate, $ToPaymentDate, $FromDate, $ToDate);

        return $FilteredBankLedger;
    }

    public function filterBankList($ID=0, $Name=0, $IsDefault=0, $Status=0, $FromDate=0, $ToDate=0)
    {
        $Banks = new Bank();
        $FilteredBankList = $Banks->filterBankList($ID, $Name, $IsDefault, $Status, $FromDate, $ToDate);

        return $FilteredBankList;
    }
}
