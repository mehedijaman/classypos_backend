<?php

namespace ClassyPOS\Models\Products;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWastes extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'product_wastes';

    protected $fillable = [
        'ShopID',
        'FloorID',
        'TerminalID',
        'ProductLedgerID',
        'Qty',
        'WastedBy',
        'Note'
    ];
}
