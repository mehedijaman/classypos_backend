<?php

namespace ClassyPOS\Models\Sales\Orders;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrders extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];
    protected $table = 'sale_orders';

    protected $fillable = [
        'UserID',
        'ShopID',
        'FloorID',
        'TerminalID',
        'CustomerID',
        'ReferenceNo',
        'Name',
        'Email',
        'Phone',
        'Address',
        'SubTotal',
        'TaxTotal',
        'ShippingCharge',
        'OtherCharge',
        'Discount',
        'Total',
        'AdvancePaid',
        'Due',
        'IsConfirmed',
        'IsDelivered',
        'OrderDate',
        'DeliveryDate',
    ];
}
