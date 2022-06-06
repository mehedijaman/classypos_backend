<?php

namespace ClassyPOS\Models\Purchase\Returns;

use ClassyPOS\Models\Products\ProductLedgers;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnProductMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "purchase_return_product_mappings";

    protected $fillable = [
        'PRID',
        'ProductLedgerID',
        'Qty',
        'Price',
    ];

    public function product()
    {
        return $this->belongsTo(ProductLedgers::class);
    }

    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturns::class);
    }
}
