<?php

namespace ClassyPOS\Models\Sales\Invoices;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleInvoice extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "sale_invoices";

    protected $fillable = [
        'UserID',
        'ShopID',
        'FloorID',
        'TerminalID',
        'KOTID',
        'OrderID',
        'AdvanceID',
        'ContactID',
        'TotalCost',
        'SubTotal',
        'TaxTotal',
        'ServiceCharge',
        'Discount',
        'Total',
        'PaidMoney',
        'ReturnMoney',
        'IsVoid',
        'IsRefunded',
        'IsPaid',
        'IsApproved',
        'Status'
    ];

    public function invoiceList($IsPaid, $from, $to)
    {
        if ($from == 0) {
            $from = '2000-01-01';
        }

        if ($to == 0) {
            $to = date('Y-m-d');
            $to = date('Y-m-d', strtotime($to . '+1 day'));
        }
        if ($IsPaid == 0) {
            $invoices = SaleInvoice::select(
                'UserID',
                'ShopID',
                'FloorID',
                'TerminalID',
                'KOTID',
                'OrderID',
                'AdvanceID',

                'ContactID',
                'contacts.DisplayName as ContactName',

                'TotalCost',
                'SubTotal',
                'TaxTotal',
                'ServiceCharge',
                'Discount',
                'Total',
                'PaidMoney',
                'ReturnMoney',
                'IsVoid',
                'IsRefunded',
                'IsPaid',
                'IsApproved',
                'sale_invoices.Status',
                'sale_invoices.created_at',
                'sale_invoices.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', 'sale_invoices.ContactID')
                ->where('sale_invoices.IsPaid', $IsPaid)
                ->whereBetween('sale_invoices.created_at', [$from, $to])
                ->WhereNull('sale_invoices.deleted_at')
                ->get();

            if(sizeof($invoices) > 0) {
                return $invoices;
            } else {
                return "No data found.";
            }
        } elseif ($IsPaid == 1) {
            $invoices = SaleInvoice::select(
                'UserID',
                'ShopID',
                'FloorID',
                'TerminalID',
                'KOTID',
                'OrderID',
                'AdvanceID',

                'ContactID',
                'contacts.DisplayName as ContactName',

                'TotalCost',
                'SubTotal',
                'TaxTotal',
                'ServiceCharge',
                'Discount',
                'Total',
                'PaidMoney',
                'ReturnMoney',
                'IsVoid',
                'IsRefunded',
                'IsPaid',
                'IsApproved',
                'sale_invoices.Status',
                'sale_invoices.created_at',
                'sale_invoices.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', 'sale_invoices.ContactID')
                ->where('sale_invoices.IsPaid', '=', $IsPaid)
                ->whereBetween('sale_invoices.created_at', [$from, $to])
                ->WhereNull('sale_invoices.deleted_at')
                ->get();

            if(sizeof($invoices) > 0) {
                return $invoices;
            } else {
                return "No data found.";
            }
        } else {
            $invoices = SaleInvoice::select(
                'UserID',
                'ShopID',
                'FloorID',
                'TerminalID',
                'KOTID',
                'OrderID',
                'AdvanceID',

                'ContactID',
                'contacts.DisplayName as ContactName',

                'TotalCost',
                'SubTotal',
                'TaxTotal',
                'ServiceCharge',
                'Discount',
                'Total',
                'PaidMoney',
                'ReturnMoney',
                'IsVoid',
                'IsRefunded',
                'IsPaid',
                'IsApproved',
                'sale_invoices.Status',
                'sale_invoices.created_at',
                'sale_invoices.updated_at'
            )
                ->leftJoin('contacts', 'contacts.id', 'sale_invoices.ContactID')
                ->whereBetween('sale_invoices.created_at', [$from, $to])
                ->WhereNull('sale_invoices.deleted_at')
                ->get();

            if(sizeof($invoices) > 0) {
                return $invoices;
            } else {
                return "No data found.";
            }
        }
    }
}
