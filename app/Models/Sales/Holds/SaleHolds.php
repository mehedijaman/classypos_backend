<?php

namespace ClassyPOS\Models\Sales\Holds;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};

class SaleHolds extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'sale_holds';

    protected $fillable = [
        'ShopID',
        'FloorID',
        'TerminalID',
        'Notes'
    ];
}
