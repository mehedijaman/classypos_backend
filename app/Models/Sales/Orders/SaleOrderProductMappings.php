<?php

namespace ClassyPOS\Models\Sales\Orders;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrderProductMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'sale_order_product_mappings';

    protected $fillable = [
        'OrderID',
        'ProductLedgerID',
        'Price',
        'Qty',
        'Status'
    ];
}
