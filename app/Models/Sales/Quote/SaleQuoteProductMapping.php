<?php

namespace ClassyPOS\Models\Sales\Quote;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleQuoteProductMapping extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'sale_quote_product_mappings';

    protected $fillable= [
        'QuoteID',
        'ProductLedgerID',
        'Qty',
        'Price',
        'TaxTotal',
        'ShippingCharge',
        'PackagingCharge',
        'OtherCharge',
        'Discount',
        'Total',
    ];
}
