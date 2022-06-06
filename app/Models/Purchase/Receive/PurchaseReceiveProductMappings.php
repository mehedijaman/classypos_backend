<?php

namespace ClassyPOS\Models\Purchase\Receive;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReceiveProductMappings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "purchase_receive_product_mappings";

    protected $fillable = [
        'ProductID',
        'PurchaseID',
        'BatchNo',
        'CustomID',
        'Model',
        'Description',
        'Qty',
        'CostPrice',
        'SalePrice',
        'ExpiredDate'
    ];

    public function product()
    {
        return $this->belongsTo('ClassyPOS\Models\Products\Products', 'ProductID');
    }

    public function purchase()
    {
        return $this->belongsTo('ClassyPOS\Models\Purchase\Receive\PurchaseReceives', 'PurchaseID');
    }
}
