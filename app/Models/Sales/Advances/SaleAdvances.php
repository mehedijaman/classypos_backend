<?php

namespace ClassyPOS\Models\Sales\Advances;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleAdvances extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "sale_advances";

    protected $fillable = [
        'UserID',
        'ShopID',
        'FloorID',
        'TerminalID',
        'ContactID',
        'Name',
        'Phone',
        'Email',
        'Address',
        'Amount',
        'Due',
        'Notes',
        'DeliveryDate',
        'Status',
    ];
}
