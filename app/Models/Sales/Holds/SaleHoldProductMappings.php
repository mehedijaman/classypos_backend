<?php

namespace ClassyPOS\Models\Sales\Holds;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};

class SaleHoldProductMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'sale_hold_product_mappings';

    protected $fillable = [
        'HoldID',
        'ProductLedgerID',
        'Qty',
        'SalePrice',
        'Discount',
        'TaxID',
    ];
}
