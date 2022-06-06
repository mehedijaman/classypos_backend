<?php

namespace ClassyPOS\Models\Employees;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLedger extends Model
{
    use SoftDeletes, usesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "employee_ledgers";

    protected $fillable = [
        'UserID',
        'EmployeeID',
        'Debit',
        'Credit',
        'Balance',
        'PaymentMethod',
        'Notes',
        'PaymentDate',
        'IsApproved',
        'Status'
    ];

    public function transaction($transaction)
    {
        $EmployeeBalance = EmployeeBalance::where('EmployeeID', $transaction->EmployeeID)->first();

        if ($transaction->EmployeeDebit != 0) {
            $EmployeeBalance->Balance = $EmployeeBalance->Balance - $transaction->EmployeeDebit;
        }
        if ($transaction->EmployeeCredit != 0) {
            $EmployeeBalance->Balance = $EmployeeBalance->Balance + $transaction->EmployeeCredit;
        }

        $EmployeeBalance->save();

        $EmployeeBalance = $EmployeeBalance->Balance;

        $EmployeeLedger = new EmployeeLedger();
        $EmployeeLedger->UserID              = $transaction->UserID;
        $EmployeeLedger->EmployeeID          = $transaction->EmployeeID;
        $EmployeeLedger->Debit               = $transaction->EmployeeDebit;
        $EmployeeLedger->Credit              = $transaction->EmployeeCredit;
        $EmployeeLedger->Balance             = $EmployeeBalance;
        $EmployeeLedger->PaymentMethod       = $transaction->PaymentMethod;
        $EmployeeLedger->Notes               = $transaction->Notes;
        $EmployeeLedger->PaymentDate         = $transaction->PaymentDate;
        $EmployeeLedger->IsApproved          = $transaction->IsApproved;
        $EmployeeLedger->Status              = $transaction->Status;

        $EmployeeLedger->save();

        $EmployeeLedger = json_encode($EmployeeLedger);

        return $EmployeeLedger;
    }

    public function filterEmployeeLedger($EmployeeID, $UserID, $IsApproved, $Status, $PaymentDateFrom, $ToPaymentDate, $FromDate, $ToDate)
    {
        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($PaymentDateFrom == 0) {
            $PaymentDateFrom = '2000-01-01';
        }

        if ($ToPaymentDate == 0) {
            $ToPaymentDate = date('Y-m-d');
            $ToPaymentDate = date('Y-m-d', strtotime($ToPaymentDate . '+1 day'));
        }

        if ($IsApproved != 0 && $Status == 0) {
            $EmployeeLedger = EmployeeLedger::select(
                'UserID',
                
                'EmployeeID',
                'employees.Name',
                
                'Debit',
                'Credit',
                'Balance',
                'PaymentMethod',
                'Notes',
                'PaymentDate',
                'IsApproved',
                'employee_ledgers.Status',

                'employee_ledgers.created_at',
                'employee_ledgers.updated_at'
            )
                ->leftJoin('employees', 'employees.id', '=', 'employee_ledgers.EmployeeID')
                ->where('IsApproved', $IsApproved)
                ->where('employee_ledgers.EmployeeID', $EmployeeID)
                ->where('employee_ledgers.UserID', $UserID)
                ->whereBetween('employee_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('employee_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('employees.deleted_at')
                ->get();

            return json_encode($EmployeeLedger);
        } elseif ($IsApproved == 0 && $Status != 0) {
            $EmployeeLedger = EmployeeLedger::select(
                'UserID',

                'EmployeeID',
                'employees.Name',

                'Debit',
                'Credit',
                'Balance',
                'PaymentMethod',
                'Notes',
                'PaymentDate',
                'IsApproved',
                'employee_ledgers.Status',

                'employee_ledgers.created_at',
                'employee_ledgers.updated_at'
            )
                ->leftJoin('employees', 'employees.id', '=', 'employee_ledgers.EmployeeID')
                ->where('employee_ledgers.Status', $Status)
                ->where('employee_ledgers.EmployeeID', $EmployeeID)
                ->where('employee_ledgers.UserID', $UserID)
                ->whereBetween('employee_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('employee_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('employees.deleted_at')
                ->get();

            return json_encode($EmployeeLedger);
        } elseif ($IsApproved != 0 && $Status != 0) {
            $EmployeeLedger = EmployeeLedger::select(
                'UserID',

                'EmployeeID',
                'employees.Name',

                'Debit',
                'Credit',
                'Balance',
                'PaymentMethod',
                'Notes',
                'PaymentDate',
                'IsApproved',
                'employee_ledgers.Status',

                'employee_ledgers.created_at',
                'employee_ledgers.updated_at'
            )
                ->leftJoin('employees', 'employees.id', '=', 'employee_ledgers.EmployeeID')
                ->where('IsApproved', $IsApproved)
                ->where('employee_ledgers.Status', $Status)
                ->where('employee_ledgers.EmployeeID', $EmployeeID)
                ->where('employee_ledgers.UserID', $UserID)
                ->whereBetween('employee_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('employee_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('employees.deleted_at')
                ->get();

            return json_encode($EmployeeLedger);
        } else {
            $EmployeeLedger = EmployeeLedger::select(
                'UserID',

                'EmployeeID',
                'employees.Name',

                'Debit',
                'Credit',
                'Balance',
                'PaymentMethod',
                'Notes',
                'PaymentDate',
                'IsApproved',
                'employee_ledgers.Status',

                'employee_ledgers.created_at',
                'employee_ledgers.updated_at'
            )
                ->where('employee_ledgers.EmployeeID', $EmployeeID)
                ->where('employee_ledgers.UserID', $UserID)
                ->leftJoin('employees', 'employees.id', '=', 'employee_ledgers.EmployeeID')
                ->whereBetween('employee_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('employee_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('employees.deleted_at')
                ->get();

            return json_encode($EmployeeLedger);
        }
    }
}
