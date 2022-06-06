<?php

Namespace ClassyPOS\Models\Products;

use ClassyPOS\Models\Contacts\Contacts;
use ClassyPOS\Models\Purchase\Receive\PurchaseReceiveProductMappings;
use ClassyPOS\Models\Purchase\Returns\PurchaseReturnProductMappings;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    use SoftDeletes, UsesTenantConnection;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'products';

    protected $fillable = [
        'UserID',
        'CategoryID',
        'ContactID',
        'BrandID',
        'TaxID',
        'Type',
        'Name',
        'Description',
        'SKU',
        'UPC',
        'MPN',
        'EAN',
        'ISBN',
        'Image',
        'OpeningQty',
        'Qty',
        'MinQtyLevel',
        'CostPrice',
        'SalePrice',
        'Unit',
        'AllowInventory',
        'AllowNegative',
        'AllowReturn',
        'AllowTax',
        'Status',
    ];

    public function ledger()
    {
        return $this->hasMany(ProductLedgers::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategories::class, 'CategoryID');
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrands::class, 'BrandID');
    }

    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'ContactID');
    }

    public function purchase_return()
    {
        return $this->hasManyThrough(
            ProductLedgers::class,
            PurchaseReturnProductMappings::class,
            'ProductLedgerID',
            'ProductID',
            'id',
            'id'
        );
    }

    public function purchaseReceiveProductMapping()
    {
        return $this->hasMany(PurchaseReceiveProductMappings::class);
    }

    public function listByCategory($Category)
    {
        $Result = DB::table('product_categories')->where('id', '=', $Category)->get();
        $Result = json_encode($Result);

        return response($Result);
    }

    /**
     * Check Product based on attributes
     * Filter Product by
     *
     * @param $CategoryID
     * @param $ContactID
     * @param $BrandID
     * @param $Status
     * @param $FromDate
     * @param $ToDate
     *
     * @return array
     */

    public function filterProductList($CategoryID, $ContactID, $BrandID, $Status, $FromDate, $ToDate)
    {
        /**
         * @ToDo
         * Future we should implement
         * Further filter
         * like
         * $IsPurchased, $Status, $AllowReturn, $SKU, $Tags,
         */

        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($CategoryID == 0 && $ContactID == 0 && $BrandID == 0 && $Status == 0) {

            /**
             * 0000
             * If no filter is selected then
             * return all product
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID == 0 && $BrandID == 0 && $Status != 0) {

            /**
             * 0001
             * If $Status filter is selected then
             * return all inactive product
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID == 0 && $BrandID != 0 && $Status == 0) {

            /**
             * 0010
             * If $BrandID filter is selected then
             * return all $BrandID product
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.BrandID', '=', $BrandID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID == 0 && $BrandID != 0 && $Status != 0) {

            /**
             * 0011
             * If $BrandID & $Status filter is selected then
             * return all $BrandID product that $Status
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.BrandID', '=', $BrandID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID != 0 && $BrandID == 0 && $Status == 0) {

            /**
             * 0100
             * If $ContactID filter is selected then
             * return all $ContactID product
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.ContactID', '=', $ContactID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID != 0 && $BrandID == 0 && $Status != 0) {

            /**
             * 0101
             * If $ContactID & $Status filter is selected then
             * return all $ContactID product that are $Status
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.ContactID', '=', $ContactID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID != 0 && $BrandID != 0 && $Status == 0) {

            /**
             * 0110
             * If $ContactID & $BrandID filter is selected then
             * return all $ContactID product that are $BrandID
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.ContactID', '=', $ContactID)
                ->where('products.BrandID', '=', $BrandID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID == 0 && $ContactID != 0 && $BrandID != 0 && $Status != 0) {

            /**
             * 0111
             * If $ContactID, $BrandID & $Status filter is selected then
             * return all $ContactID, $BrandID product that are $Status
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.ContactID', '=', $ContactID)
                ->where('products.BrandID', '=', $BrandID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID == 0 && $BrandID == 0 && $Status == 0) {

            /**
             * 1000
             * If $CategoryID filter is selected then
             * return all $CategoryID product
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID == 0 && $BrandID == 0 && $Status != 0) {

            /**
             * 1001
             * If $CategoryID & $Status filter is selected then
             * return all $CategoryID product that are $Status
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID == 0 && $BrandID != 0 && $Status == 0) {

            /**
             * 1010
             * If $CategoryID & $BrandID filter is selected then
             * return all $CategoryID product that are $BrandID
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.BrandID', '=', $BrandID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID == 0 && $BrandID != 0 && $Status != 0) {

            /**
             * 1011
             * If $CategoryID, $BrandID & $Status filter is selected then
             * return all $CategoryID, $BrandID product that are $Status
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.BrandID', '=', $BrandID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID != 0 && $BrandID == 0 && $Status == 0) {

            /**
             * 1100
             * If $CategoryID & $ContactID filter is selected then
             * return all $CategoryID product that are $ContactID
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.ContactID', '=', $ContactID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID != 0 && $BrandID == 0 && $Status != 0) {

            /**
             * 1101
             * If $CategoryID & $ContactID filter is selected then
             * return all $CategoryID product that are $ContactID
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.ContactID', '=', $ContactID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } elseif ($CategoryID != 0 && $ContactID != 0 && $BrandID != 0 && $Status == 0) {

            /**
             * 1110
             * If $CategoryID, $ContactID & $BrandID filter is selected then
             * return all $CategoryID, $ContactID product that are $BrandID
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.ContactID', '=', $ContactID)
                ->where('products.BrandID', '=', $BrandID)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        } else {

            /**
             * 1111
             * If $CategoryID, $ContactID, $BrandID & $Status filter is selected then
             * return all $CategoryID, $ContactID, $BrandID product that are $Status
             */

            $Products = DB::table('products')->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',
                'taxes.Percent',

                'products.Name',
                'products.Description',
                'products.Image',
                'products.OpeningQty',
                'products.Qty',
                'products.MinQtyLevel',
                'products.CostPrice',
                'products.SalePrice',
                'products.Unit',
                'products.AllowNegative',
                'products.AllowReturn',
                'products.SKU',
                'products.AllowTax',
                'products.Status',
                'products.deleted_at',
                'products.created_at',
                'products.updated_at'
            )
                ->leftJoin('product_categories', 'products.CategoryID', '=', 'product_categories.id')
                ->leftJoin('contacts', 'products.ContactID', '=', 'contacts.id')
                ->leftJoin('product_brands', 'products.BrandID', '=', 'product_brands.id')
                ->leftJoin('taxes', 'products.TaxID', '=', 'taxes.id')
                ->where('products.CategoryID', '=', $CategoryID)
                ->where('products.ContactID', '=', $ContactID)
                ->where('products.BrandID', '=', $BrandID)
                ->where('products.Status', '=', $Status)
                ->whereBetween('products.created_at', [$FromDate, $ToDate])
                ->whereNull('products.deleted_at')
                ->paginate(10);

            $Products = json_encode($Products);

        }

        return response($Products);
    }

}
