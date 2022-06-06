<?php

namespace ClassyPOS\Models\Products;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategories extends Model
{
    use SoftDeletes, UsesTenantConnection;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = "product_categories";

    protected $fillable = [
        'Name',
        'Image',
        'Color',
        'Status'
    ];

    public function product()
    {
        return $this->hasMany(Products::class);
    }
}
