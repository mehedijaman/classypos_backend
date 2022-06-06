<?php

namespace ClassyPOS\Models\Products;

use ClassyPOS\Models\Purchase\Receive\PurchaseReceives;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductLedgers extends Model
{
    use SoftDeletes, UsesTenantConnection;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = "product_ledgers";

    protected $fillable = [
        'ProductID',
        'PurchaseID',
        'WarehouseID',
        'RackID',
        'BatchNo',
        'CustomID',
        'Model',
        'Color',
        'Description',
        'Qty',
        'CostPrice',
        'SalePrice',
        'Barcode',
        'Status',
        'ExpiredDate'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'ProductID');
    }

    public function purchase()
    {
        return $this->belongsTo(PurchaseReceives::class, 'PurchaseID');
    }

    /**
     * Check Product based on attributes
     * Filter Product by
     * from product_ledger table.
     * 
     * @param $ProductID
     * @param $PurchaseID
     * @param $WarehouseID
     * @param $RackID
     * @param $BatchNo
     * 
     * Filter Product by
     * from products table.
     * @param $CategoryID
     * @param $ContactID
     * @param $BrandID
     * @param $Status
     * @param $FromDate
     * @param $ToDate
     *
     * @return array
     */

    public function filterProductLedger($ProductID, $PurchaseID, $WarehouseID, $RackID, $BatchNo, $Status, $FromDate, $ToDate)
    {
        /**
         * @ToDo
         * Future we should implement
         * Further filter
         * like
         * $IsPurchased, $Status, $AllowReturn, $HasAttribute, $Tags,
         */

        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($ProductID == 0 && $PurchaseID == 0 && $WarehouseID == 0 && $RackID == 0 && $BatchNo == 0 && $Status == 0) {

            /**
             * 000000
             * If no filter is selected then
             * return all products
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($ProductID != 0 && $PurchaseID == 0 && $WarehouseID == 0 && $RackID == 0 && $BatchNo == 0 && $Status == 0) {

            /**
             * 100000
             * Filter product by
             * $ProductID
             * return all product_ledger
             * that matches the $ProductID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name as RackName',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product_ledgers.ProductID', '=', $ProductID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($ProductID == 0 && $PurchaseID != 0 && $WarehouseID == 0 && $RackID == 0 && $BatchNo == 0 && $Status == 0) {

            /**
             * 010000
             * Filter product by
             * $PurchaseID
             * return all product_ledger
             * that matches the $PurchaseID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product_ledgers.PurchaseID', '=', $PurchaseID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($ProductID == 0 && $PurchaseID == 0 && $WarehouseID != 0 && $RackID == 0 && $BatchNo == 0 && $Status == 0) {

            /**
             * 001000
             * Filter product by
             * $WarehouseID
             * return all product_ledger
             * that matches the $WarehouseID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product_ledgers.WarehouseID', '=', $WarehouseID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($ProductID == 0 && $PurchaseID == 0 && $WarehouseID == 0 && $RackID != 0 && $BatchNo == 0 && $Status == 0) {

            /**
             * 000100
             * Filter product by
             * $RackID
             * return all product_ledger
             * that matches the $RackID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product_ledgers.RackID', '=', $RackID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($ProductID == 0 && $PurchaseID == 0 && $WarehouseID == 0 && $RackID == 0 && $BatchNo != 0 && $Status == 0) {

            /**
             * 000010
             * Filter product by
             * $BatchNo
             * return all product_ledger
             * that matches the $BatchNo
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product_ledgers.BatchNo', '=', $BatchNo)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($ProductID == 0 && $PurchaseID == 0 && $WarehouseID == 0 && $RackID == 0 && $BatchNo == 0 && $Status != 0) {

            /**
             * 000001
             * Filter product by
             * $Status
             * return all product_ledger
             * that matches the $Status
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product_ledgers.Status', '=', $Status)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } else {
            $Products = "M y   w h o l e   l i f e   i s   a   l i e !";
        }

        return response($Products);
    }


    public function filterProduct($CategoryID, $ContactID, $FromDate, $ToDate)
    {
        /**
         * @ToDo
         * Future we should implement
         * Further filter
         * like
         * $IsPurchased, $Status, $AllowReturn, $HasAttribute, $Tags,
         */

        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($CategoryID == 0 && $ContactID == 0) {

            /**
             * 00
             * If no filter is selected then
             * return all product
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',

                'product_ledgers.RackID',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->get();

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID == 0) {

            /**
             * 10
             * Filter product by
             * $CategoryID
             * return all products
             * that matches the $CategoryID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',

                'product_ledgers.RackID',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(100);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID != 0) {

            /**
             * 01
             * Filter product by
             * $ContactID
             * return all products
             * that matches the $ContactID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('products.ContactID', '=', $ContactID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(100);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID != 0) {
            /**
             * 11
             * Filter product by
             * $CategoryID & $ContactID
             * return all products
             * that matches the $CategoryID & $ContactID
             */

            $Products = DB::table('product_ledgers')->select(
                'product_ledgers.id',

                'product_ledgers.ProductID',
                'products.Name as Name',

                'product_ledgers.PurchaseID',

                'product_ledgers.WarehouseID',
                'product_warehouses.Name as WarehouseName',

                'product_ledgers.RackID',
                'product_racks.Name',

                'product_ledgers.BatchNo',
                'product_ledgers.CustomID',
                'product_ledgers.Model',
                'product_ledgers.Color',
                'product_ledgers.Description',
                'product_ledgers.Qty',
                'product_ledgers.CostPrice',
                'product_ledgers.SalePrice',
                'product_ledgers.Barcode',
                'product_ledgers.Status',
                'product_ledgers.ExpiredDate',

                'product_ledgers.deleted_at',
                'product_ledgers.created_at',
                'product_ledgers.updated_at'
            )
                ->leftJoin('products', 'product_ledgers.ProductID', '=', 'products.id')
                ->leftJoin('purchase_receives', 'product_ledgers.PurchaseID', '=', 'purchase_receives.id')
                ->leftJoin('product_warehouses', 'product_ledgers.WarehouseID', '=', 'product_warehouses.id')
                ->leftJoin('product_racks', 'product_ledgers.RackID', '=', 'product_racks.id')
                ->where('product.CategoryID', '=', $CategoryID)
                ->where('product.ContactID', '=', $ContactID)
                ->whereBetween('product_ledgers.created_at', [$FromDate, $ToDate])
                ->whereNull('product_ledgers.deleted_at')
                ->paginate(100);

            $Products = json_encode($Products);

        } else {
            $Products = "M y   w h o l e   l i f e   i s   a   l i e !";
        }

        return response($Products);
    }
}
