<?php

namespace ClassyPOS\Models\Purchase\Order;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};

class PurchaseOrders extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "purchase_orders";

    protected $fillable = [
        'VendorID',
        'ReferenceNo',
        'Notes',
        'DeliveryAddress',
        'DeliveryDate',
        'SubTotal',
        'ShippingCharge',
        'TaxCharge',
        'OtherCharge',
        'Total',
    ];
}
