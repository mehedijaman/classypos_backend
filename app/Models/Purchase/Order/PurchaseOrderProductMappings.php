<?php

namespace ClassyPOS\Models\Purchase\Order;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderProductMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "purchase_order_product_mappings";

    protected $fillable = [
        'PurchaseOrderID',
        'ProductID',
        'Description',
        'Model',
        'Price',
        'Qty'
    ];
}
