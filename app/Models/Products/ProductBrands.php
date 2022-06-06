<?php

namespace ClassyPOS\Models\Products;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrands extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $times = ['deleted_at'];

    protected $table = "product_brands";

    protected $fillable = [
        'Name',
        'Manufacturer'
    ];

    public function product()
    {
        return $this->hasMany(Products::class);
    }
}
