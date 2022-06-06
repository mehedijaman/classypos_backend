<?php

namespace ClassyPOS\Models\Purchase\Returns;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturns extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "purchase_returns";

    protected $fillable = [
        'UserID',
        'ShopID',
        'PurchaseID',
        'VendorID',
        'MemoNo',
        'TotalPrice',
        'ReturnReason'
    ];
}
