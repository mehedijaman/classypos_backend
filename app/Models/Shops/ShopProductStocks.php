<?php

namespace ClassyPOS\Models\Shops;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProductStocks extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'shop_product_stocks';

    protected $fillable = [
        'ShopID',
        'FloorID',
        'TerminalID',
        'ProductLedgerID',
        'Qty',
        'Status',
    ];
}
