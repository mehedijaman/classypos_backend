<?php

namespace ClassyPOS\Models\Products;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBarcodeSettings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'product_barcode_settings';
    protected $fillable = [
        'ShopID',
        'ShopName',
        'ShowProductID',
        'ShowShopName',
        'ShowSalePrice',
        'ShowExpireDate'
    ];
}
