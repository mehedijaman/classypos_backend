<?php

namespace ClassyPOS\Models\Sales\Invoices;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleInvoiceProductMapping extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "sale_invoice_product_mappings";

    protected $fillable = [
        'ShopID',
        'InvoiceID',
        'ProductLedgerID',
        'Qty',
        'CostPrice',
        'SalePrice',
        'TaxTotal',
        'Discount',
        'TotalPrice'
    ];
}
