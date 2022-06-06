<?php

namespace ClassyPOS\Models\Kitchens;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KitchenCategoryMapping extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "kitchen_category_mappings";

    protected $fillable = [
        'KitchenID',
        'CategoryID'
    ];
}
