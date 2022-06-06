<?php

namespace ClassyPOS\Models\Shops;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProductLedgers extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'shop_product_ledgers';

    protected $fillable = [
        'UserID',
        'ShopID',
        'ProductLedgerID',
        'FloorID',
        'TerminalID',
        'Notes',
        'Qty',
    ];
}
