<?php

namespace ClassyPOS\Models\Purchase\Receive;

use ClassyPOS\Models\Contacts\Contacts;
use ClassyPOS\Models\Products\ProductLedgers;
use ClassyPOS\Models\Products\Products;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReceives extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "purchase_receives";

    protected $fillable = [
        'UserID',
        'PurchaseOrderID',
        'ContactID',
        'MemoNo',
        'SubTotal',
        'ShippingCharge',
        'TaxCharge',
        'OtherCharge',
        'Total',
    ];

    public function mapped_product()
    {
        return $this->hasMany(PurchaseReceiveProductMappings::class);
    }

    public function ledger()
    {
        return $this->hasMany(ProductLedgers::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'ContactID');
    }
}
