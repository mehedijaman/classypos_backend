<?php

namespace ClassyPOS\Models\Sales\Advances;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleAdvanceProductMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'sale_advance_product_mappings';

    protected $fillable = [
        'AdvanceID',
        'ProductLedgerID',
        'Qty',
        'SalePrice',
        'Discount',
        'TaxID',
    ];
}
