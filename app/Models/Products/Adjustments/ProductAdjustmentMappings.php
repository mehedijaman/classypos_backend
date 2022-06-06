<?php

namespace ClassyPOS\Models\Products\Adjustments;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAdjustmentMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'product_adjustment_mappings';
    protected $fillable = [
        'ProductID',
        'Qty',
        'SalePrice'
    ];
}
