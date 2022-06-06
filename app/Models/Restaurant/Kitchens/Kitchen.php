<?php

namespace ClassyPOS\Models\Kitchens;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kitchen extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "kitchens";

    protected $fillable = [
        'ShopID',
        'Name',
        'IsOpen'
    ];
}
