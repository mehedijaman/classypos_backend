<?php

namespace ClassyPOS\Models\Shops;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopInvoiceSettings extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = 'deleted_at';

    protected $table = 'shop_invoice_settings';
    protected $fillable = [
        'ShopIDFloorID',
        'TerminalID',
        'Header',
        'Footer',
        'ShowDiscount',
        'ShowPhone',
        'ShowInvoiceID',
        'ShowVatReg',
        'ShowProductID',
        'ShowTotalQty',
        'ShowHeader',
        'ShowFooter',
        'ShowLogo',
        'ShowTax',
        'ShowAddress',
        'ShowEmail',
        'SaleInvoiceSize',
        'PurchaseInvoiceSize',
        'FontSize',
        'FontFamily',
        'LogoWidth',
        'LogoHeight',
    ];
}
