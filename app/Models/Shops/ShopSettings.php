<?php

namespace ClassyPOS\Models\Shops;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopSettings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "shop_settings";

    protected $fillable = [
        'ShopID',
        'IsRestaurant',
        'IsServiceCharge',
        'ServiceCharge',
        'IsTips',
        'IsTax',
        'IsOrder',
        'IsHold',
        'IsAdvance',
        'IsBarcode',
        'IsRefund',
        'IsDiscount',
        'CurrencyName',
        'CurrencySymbol',
    ];
}
