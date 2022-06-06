<?php

namespace ClassyPOS\Models\Banks;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankLedger extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $times = ['deleted_at'];

    protected $table = "bank_ledgers";

    protected $fillable = [
        'UserID',
        'BankID',
        'ChequeNumber',
        'RefChequeNumber',
        'RefBank',
        'Deposit',
        'Withdraw',
        'Balance',
        'TxBy',
        'Notes',
        'Status',
        'Date'
    ];

    public function transaction($transaction)
    {
        $BankBalance = BankBalance::where('BankID', $transaction->BankID)->first();

        if ($transaction->Withdraw != 0) {
            $BankBalance->Balance = $BankBalance->Balance - $transaction->Withdraw;
        }

        if ($transaction->Deposit != 0) {
            $BankBalance->Balance = $BankBalance->Balance + $transaction->Deposit;
        }

        $BankBalance->save();

        $Balance = $BankBalance->Balance;

        $Transaction = new BankLedger();
        $Transaction->UserID            = $transaction->UserID;
        $Transaction->BankID            = $transaction->BankID;
        $Transaction->ChequeNumber      = $transaction->ChequeNumber;
        $Transaction->RefChequeNumber   = $transaction->RefChequeNumber;
        $Transaction->RefBank           = $transaction->RefBank;
        $Transaction->Deposit           = $transaction->Deposit;
        $Transaction->Withdraw          = $transaction->Withdraw;
        $Transaction->Balance           = $Balance;
        $Transaction->TxBy              = $transaction->TxBy;
        $Transaction->Notes             = $transaction->Notes;
        $Transaction->Status            = $transaction->Status;
        $Transaction->Date              = $transaction->Date;

        $Transaction->save();

        return $Transaction = json_encode($Transaction);
    }

    public function filteredLedger($BankID, $UserID, $Status, $FromPaymentDate, $ToPaymentDate, $FromDate, $ToDate)
    {
        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($FromPaymentDate == 0) {
            $FromPaymentDate = '2000-01-01';
        }

        if ($ToPaymentDate == 0) {
            $ToPaymentDate = date('Y-m-d');
            $ToPaymentDate = date('Y-m-d', strtotime($ToPaymentDate . '+1 day'));
        }

        if ($UserID != 0 && $Status == 0) {
            $BankLedger = BankLedger::select(
                'UserID',
                'BankID',
                'banks.Name',
                'ChequeNumber',
                'RefChequeNumber',
                'RefBank',
                'Deposit',
                'Withdraw',
                'Balance',
                'TxBy',
                'Notes',
                'bank_ledgers.Status',
                'Date',
                'bank_ledgers.created_at',
                'bank_ledgers.updated_at'
            )
                ->leftJoin('banks', 'banks.id', '=', 'bank_ledgers.BankID')
                ->where('BankID', $BankID)
                ->where('UserID', $UserID)
                ->whereBetween('bank_ledgers.created_at', [$FromDate, $ToDate])
                ->whereBetween('Date', [$FromPaymentDate, $ToPaymentDate])
                ->WhereNull('banks.deleted_at')
                ->get();

            return json_encode($BankLedger);
        } elseif ($UserID == 0 && $Status != 0) {
            $BankLedger = BankLedger::select(
                'UserID',
                'BankID',
                'banks.Name',
                'ChequeNumber',
                'RefChequeNumber',
                'RefBank',
                'Deposit',
                'Withdraw',
                'Balance',
                'TxBy',
                'Notes',
                'bank_ledgers.Status',
                'Date',
                'bank_ledgers.created_at',
                'bank_ledgers.updated_at'
            )
                ->leftJoin('banks', 'banks.id', '=', 'bank_ledgers.BankID')
                ->where('BankID', $BankID)
                ->where('Status', $Status)
                ->whereBetween('bank_ledgers.created_at', [$FromDate, $ToDate])
                ->whereBetween('Date', [$FromPaymentDate, $ToPaymentDate])
                ->WhereNull('banks.deleted_at')
                ->get();

            return json_encode($BankLedger);
        } elseif ($UserID != 0 && $Status != 0) {
            $BankLedger = BankLedger::select(
                'UserID',
                'BankID',
                'banks.Name',
                'ChequeNumber',
                'RefChequeNumber',
                'RefBank',
                'Deposit',
                'Withdraw',
                'Balance',
                'TxBy',
                'Notes',
                'bank_ledgers.Status',
                'Date',
                'bank_ledgers.created_at',
                'bank_ledgers.updated_at'
            )
                ->leftJoin('banks', 'banks.id', '=', 'bank_ledgers.BankID')
                ->where('BankID', $BankID)
                ->where('UserID', $UserID)
                ->where('Status', $Status)
                ->whereBetween('bank_ledgers.created_at', [$FromDate, $ToDate])
                ->whereBetween('Date', [$FromPaymentDate, $ToPaymentDate])
                ->WhereNull('banks.deleted_at')
                ->get();

            return json_encode($BankLedger);
        } else {
            $BankLedger = BankLedger::select(
                'UserID',
                'BankID',
                'banks.Name',
                'ChequeNumber',
                'RefChequeNumber',
                'RefBank',
                'Deposit',
                'Withdraw',
                'Balance',
                'TxBy',
                'Notes',
                'bank_ledgers.Status',
                'Date',
                'bank_ledgers.created_at',
                'bank_ledgers.updated_at'
            )
                ->where('BankID', $BankID)
                ->leftJoin('banks', 'banks.id', '=', 'bank_ledgers.BankID')
                ->whereBetween('bank_ledgers.created_at', [$FromDate, $ToDate])
                ->whereBetween('Date', [$FromPaymentDate, $ToPaymentDate])
                ->WhereNull('banks.deleted_at')
                ->get();

            return json_encode($BankLedger);
        }
    }
}
