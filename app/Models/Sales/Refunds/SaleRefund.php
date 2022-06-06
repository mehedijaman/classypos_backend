<?php

namespace ClassyPOS\Models\Sales;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleRefund extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'sale_refunds';

    protected $fillable = [
        'ShopID',
        'FloorID',
        'TerminalID',
        'UserID',
        'InvoiceID',
        'ProductLedgerID',
        'Qty',
        'Price',
        'TaxTotal',
        'Discount',
        'TotalPrice',
        'RefundReason'
    ];
}
