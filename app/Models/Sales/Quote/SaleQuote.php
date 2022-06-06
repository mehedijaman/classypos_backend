<?php

namespace ClassyPOS\Models\Sales\Quote;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleQuote extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'sale_quotes';

    protected $fillable = [
        'ShopID',
        'UserID',
        'CustomerID',
        'Title',
        'SubTotal',
        'TaxTotal',
        'ShippingCharge',
        'PackagingCharge',
        'OtherCharge',
        'Discount',
        'Total',
        'Notes',
        'Status',
        'ExpiredDate',
    ];
}
