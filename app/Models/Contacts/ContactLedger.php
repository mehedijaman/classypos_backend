<?php

namespace ClassyPOS\Models\Contacts;

use GuzzleHttp\Psr7\Request;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactLedger extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "contact_ledgers";

    protected $fillable = [
        'UserID',
        'ContactID',
        'InvoiceID',
        'PurchaseOrderID',
        'PurchaseInvoiceID',
        'MemoNo',
        'Debit',
        'Credit',
        'Balance',
        'PaymentMethod',
        'Notes',
        'DueDate',
        'PaymentDate',
        'IsApproved',
        'Status',
    ];

    public function transaction($transaction)
    {
        $ContactBalance = ContactBalance::where('ContactID', $transaction->ContactID)->first();

        if ($transaction->ContactDebit != 0) {
            $ContactBalance->Balance = $ContactBalance->Balance - $transaction->ContactDebit;
        }
        if ($transaction->ContactCredit != 0) {
            $ContactBalance->Balance = $ContactBalance->Balance + $transaction->ContactCredit;
        }

        $ContactBalance->save();

        $ContactBalance = $ContactBalance->Balance;

        $ContactLedger = new ContactLedger();
        $ContactLedger->UserID              = $transaction->UserID;
        $ContactLedger->ContactID           = $transaction->ContactID;
        $ContactLedger->InvoiceID           = $transaction->InvoiceID;
        $ContactLedger->PurchaseOrderID     = $transaction->PurchaseOrderID;
        $ContactLedger->PurchaseInvoiceID   = $transaction->PurchaseInvoiceID;
        $ContactLedger->MemoNo              = $transaction->MemoNo;
        $ContactLedger->Debit               = $transaction->ContactDebit;
        $ContactLedger->Credit              = $transaction->ContactCredit;
        $ContactLedger->Balance             = $ContactBalance;
        $ContactLedger->PaymentMethod       = $transaction->PaymentMethod;
        $ContactLedger->Notes               = $transaction->Notes;
        $ContactLedger->DueDate             = $transaction->DueDate;
        $ContactLedger->PaymentDate         = $transaction->PaymentDate;
        $ContactLedger->IsApproved          = $transaction->IsApproved;
        $ContactLedger->Status              = $transaction->Status;

        $ContactLedger->save();

        $ContactLedger = json_encode($ContactLedger);

        return $ContactLedger;
    }

    public function filterContactLedger($ContactID, $UserID, $IsApproved, $Status, $DueDateFrom, $ToDueDate, $PaymentDateFrom, $ToPaymentDate, $FromDate, $ToDate)
    {
        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($DueDateFrom == 0) {
            $DueDateFrom = '2000-01-01';
        }

        if ($ToDueDate == 0) {
            $ToDueDate = date('Y-m-d');
            $ToDueDate = date('Y-m-d', strtotime($ToDueDate . '+1 day'));
        }

        if ($PaymentDateFrom == 0) {
            $PaymentDateFrom = '2000-01-01';
        }

        if ($ToPaymentDate == 0) {
            $ToPaymentDate = date('Y-m-d');
            $ToPaymentDate = date('Y-m-d', strtotime($ToPaymentDate . '+1 day'));
        }

        if ($IsApproved != 0 && $Status == 0) {
            $ContactLedger = ContactLedger::select(
                'UserID',

                'ContactID',
                'contacts.CompanyName',

                'InvoiceID',
                'PurchaseOrderID',
                'PurchaseInvoiceID',
                'MemoNo',
                'Debit',
                'Credit',
                'Balance',
                'contact_ledgers.PaymentMethod',
                'contact_ledgers.Notes',
                'DueDate',
                'PaymentDate',
                'IsApproved',
                'contact_ledgers.Status',

                'contact_ledgers.created_at',
                'contact_ledgers.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', '=', 'contact_ledgers.ContactID')
                ->where('IsApproved', $IsApproved)
                ->where('contact_ledgers.ContactID', $ContactID)
                ->where('contact_ledgers.UserID', $UserID)
                ->whereBetween('contact_ledgers.DueDate', [$DueDateFrom, $ToDueDate])
                ->whereBetween('contact_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('contact_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('contacts.deleted_at')
                ->get();

            return json_encode($ContactLedger);
        } elseif ($IsApproved == 0 && $Status != 0) {
            $ContactLedger = ContactLedger::select(
                'UserID',

                'ContactID',
                'contacts.CompanyName',

                'InvoiceID',
                'PurchaseOrderID',
                'PurchaseInvoiceID',
                'MemoNo',
                'Debit',
                'Credit',
                'Balance',
                'contact_ledgers.PaymentMethod',
                'contact_ledgers.Notes',
                'DueDate',
                'PaymentDate',
                'IsApproved',
                'contact_ledgers.Status',

                'contact_ledgers.created_at',
                'contact_ledgers.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', '=', 'contact_ledgers.ContactID')
                ->where('Status', $Status)
                ->where('contact_ledgers.ContactID', $ContactID)
                ->where('contact_ledgers.UserID', $UserID)
                ->whereBetween('contact_ledgers.DueDate', [$DueDateFrom, $ToDueDate])
                ->whereBetween('contact_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('contact_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('contacts.deleted_at')
                ->get();

            return json_encode($ContactLedger);
        } elseif ($IsApproved != 0 && $Status != 0) {
            $ContactLedger = ContactLedger::select(
                'UserID',

                'ContactID',
                'contacts.CompanyName',

                'InvoiceID',
                'PurchaseOrderID',
                'PurchaseInvoiceID',
                'MemoNo',
                'Debit',
                'Credit',
                'Balance',
                'contact_ledgers.PaymentMethod',
                'contact_ledgers.Notes',
                'DueDate',
                'PaymentDate',
                'IsApproved',
                'contact_ledgers.Status',

                'contact_ledgers.created_at',
                'contact_ledgers.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', '=', 'contact_ledgers.ContactID')
                ->where('IsApproved', $IsApproved)
                ->where('Status', $Status)
                ->where('contact_ledgers.ContactID', $ContactID)
                ->where('contact_ledgers.UserID', $UserID)
                ->whereBetween('contact_ledgers.DueDate', [$DueDateFrom, $ToDueDate])
                ->whereBetween('contact_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('contact_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('contacts.deleted_at')
                ->get();

            return json_encode($ContactLedger);
        } else {
            $ContactLedger = ContactLedger::select(
                'UserID',

                'ContactID',
                'contacts.CompanyName',

                'InvoiceID',
                'PurchaseOrderID',
                'PurchaseInvoiceID',
                'MemoNo',
                'Debit',
                'Credit',
                'Balance',
                'contact_ledgers.PaymentMethod',
                'contact_ledgers.Notes',
                'DueDate',
                'PaymentDate',
                'IsApproved',
                'contact_ledgers.Status',

                'contact_ledgers.created_at',
                'contact_ledgers.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', '=', 'contact_ledgers.ContactID')
                ->where('contact_ledgers.ContactID', $ContactID)
                ->where('contact_ledgers.UserID', $UserID)
                ->whereBetween('contact_ledgers.DueDate', [$DueDateFrom, $ToDueDate])
                ->whereBetween('contact_ledgers.PaymentDate', [$PaymentDateFrom, $ToPaymentDate])
                ->whereBetween('contact_ledgers.created_at', [$FromDate, $ToDate])
                ->WhereNull('contacts.deleted_at')
                ->get();

            return json_encode($ContactLedger);
        }
    }
}
