<?php

namespace ClassyPOS\Models\Products\Adjustments;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAdjustmentCategories extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'product_adjustments';
    protected $fillable = [
        'UserID',
        'Reference',
        'Date',
        'Notes'
    ];
}
