<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductLedgers;
use ClassyPOS\Models\Products\Products;
use ClassyPOS\Models\Purchase\Receive\PurchaseReceives;
use ClassyPOS\Models\Purchase\Returns\PurchaseReturnProductMappings;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Product manipulation
     * Create, Update, Find Product
     * return @void
     *
     * */
    public function listProduct()
    {
//        $Products = Products::with(['category', 'brand', 'contact'])->paginate(10);

        $Products = Products::with(
            [
                'category' => function ($query) {$query->select('id', 'Name');},
                'brand' => function ($query) {$query->select('id', 'Name');},
                'contact' => function ($query) {$query->select('id', 'CompanyName');}
            ]
        )->paginate(10);

        return response()->json($Products);
    }

    public function listTrash()
    {
        // view only trashed items
        return Products::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ProductID)
    {
        // Recover soft deleted items back to list
        Products::withTrashed()->find($ProductID)->restore();
    }

    public function clearTrash($ProductID)
    {
        // Permanently Delete
        Products::withTrashed()->find($ProductID)->forceDelete();
    }


    public function showProduct($ProductID)
    {
        // View specific Product @param Product $ProductID.

        $Product = Products::with(['category', 'brand', 'contact'])->where('id', '=', $ProductID)->first();

        return response()->json($Product, 200);
    }

    public function storeProduct(Request $request)
    {

//        // if file is not selected it will be empty string
//        if ($request->file('Image') == "")
//        {
//            $ImageName = "No Image";
//        }
//        else
//        {
//            // retrieve original file path
//            $ImageTempName = $request->file('Image')->getPathName();
//            //retrieve original file name
//            $ImageName = $request->file('Image')->getClientOriginalName();
//            // define path to upload image
//            $Path = base_path() . '/uploads/image/product';
//            // upload image to defined path directory
//            $request->file('Image')->move($Path, $ImageName);
//        }

        $ProductID = Products::create($request->all());

        $ProductID = $ProductID->id;

        $Product = Products::where('products.id', '=', $ProductID)
            ->leftjoin('product_categories', 'product_categories.id', '=', 'products.CategoryID')
            ->leftjoin('product_brands', 'product_brands.id', '=', 'products.BrandID')
            ->leftjoin('contacts', 'contacts.id', '=', 'products.ContactID')
            ->select(
                'products.id',

                'products.CategoryID',
                'product_categories.Name as CategoryName',

                'products.ContactID',
                'contacts.CompanyName as ContactName',

                'products.BrandID',
                'product_brands.Name as BrandName',

                'products.TaxID',

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
            )->first();

        return response()->json($Product, 201);
    }

    public function bulkInsertProduct(Request $request)
    {
        $Products = $request->Products;
        $Now = Carbon::now()->format('Y-m-d H:i:s');

        // Add current timestamp as created_at & updated_at time on insert
        for ($i=0; $i<sizeof($Products); $i++) {
            $Products[$i] += ['created_at' => $Now];
            $Products[$i] += ['updated_at' => $Now];
        }

        // Insert products to the products table
        // And get the id
        try {
            DB::beginTransaction();

            // 1- get the last id of your table ($lastIdBeforeInsertion)
            $lastIdBeforeInsertion = Products::find(DB::table('products')->max('id'));

            // If product table is empty and
            // Inserting products to the table first time
            // Then the id should be 0
            if (empty($lastIdBeforeInsertion)) {
                $lastIdBeforeInsertion = 0;
            } else {
                $lastIdBeforeInsertion = $lastIdBeforeInsertion->id;
            }

            // 2- insert your data
            Products::insert($Products);

            // 3- Getting the last inserted ids
            $ProductIds = [];
            for($i=1; $i<=sizeof($Products); $i++) {
                array_push($ProductIds, $lastIdBeforeInsertion + $i);
            }

            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollback();
        }

        $Items = $request->ProductLedger;

        // Insert previously added ProductID  on product_ledgers table
        for ($i=0; $i<sizeof($ProductIds); $i++) {
            $Items[$i] += ['ProductID' => $ProductIds[$i]];
        }

        // Add current timestamp as created_at & updated_at time on insert
        for ($i=0; $i<sizeof($Items); $i++) {
            $Items[$i] += ['created_at' => $Now];
            $Items[$i] += ['updated_at' => $Now];
        }

        // Insert products to the product_ledger table
        try {
            ProductLedgers::insert($Items);

            $Status = "Status: Ok";
        }
        catch (\Exception $exception) {
            $Status = $exception;
        }

        return response()->json($Status, 201);
    }


    public function updateProduct(Request $request, Products $ProductID)
    {
        $ProductID->update($request->all());

        return response()->json($ProductID, 200);
    }

    public function destroyProduct(Products $ProductID)
    {
        $ProductID->delete();

        return response()->json(null, 204);
    }

    public function getProductLedgerByProductID($ProductID)
    {
        $ProductLedger = ProductLedgers::select(
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
            ->where('ProductID', '=', $ProductID)
            ->whereNull('product_ledgers.deleted_at')
            ->get();

        if (count($ProductLedger) > 0) {
            return response()->json($ProductLedger, 200);
        } else {
            return response()->json('Sorry this product have No Ledger entry.', 200);
        }
    }

    public function filterProductList($CategoryID=0, $ContactID=0, $BrandID=0, $InactiveProduct=0, $FromDate=0, $ToDate=0)
    {
        $Products = new Products();

        $FilteredProductList = $Products->filterProductList(
            $CategoryID,
            $ContactID,
            $BrandID,
            $InactiveProduct,
            $FromDate,
            $ToDate
        );

        return $FilteredProductList;
    }

    public function filterProductLedger($ProductID=0, $PurchaseID=0, $WarehouseID=0, $RackID=0, $BatchNo=0, $Status=0, $FromDate=0, $ToDate=0)
    {
        $ProductLedgers = new ProductLedgers();

        return $ProductLedgers->filterProductLedger(
            $ProductID,
            $PurchaseID,
            $WarehouseID,
            $RackID,
            $BatchNo,
            $Status,
            $FromDate,
            $ToDate
        );
    }


    public function filterProduct($CategoryID=0, $ContactID=0, $FromDate=0, $ToDate=0)
    {
        $ProductLedgers = new ProductLedgers();

        return $ProductLedgers->filterProduct($CategoryID, $ContactID, $FromDate, $ToDate);
    }

    public function testLedger($PRID)
    {
        $Products = PurchaseReturnProductMappings::with('product')
            ->where('PRID', '=', $PRID)->get();

        return response()->json($Products, 200);
    }

    public function ledgerTest(Request $request)
    {
        if ($request->has('ProductID')) {
            $Products = ProductLedgers::with(['product' => function ($query) use($request) {
                $query->where('id', '=', $request->ProductID);
            }])->where('ProductID', '=', $request->ProductID)->get();
        }
        elseif ($request->has('PurchaseID')) {
            $Products = ProductLedgers::with(['purchase' => function ($query) use($request) {
                $query->where('id', '=', $request->PurchaseID);
            }])->where('PurchaseID', '=', $request->PurchaseID)->get();
        }
        elseif ($request->has('ProductID') && $request->has('PurchaseID')) {
            $Products = ProductLedgers::with([
                'product' => function ($query) use($request) {
                    $query->where('id', '=', $request->ProductID);
                },
                'purchase' => function ($query) use($request) {
                    $query->where('id', '=', $request->PurchaseID);
                }
            ])
                ->where('ProductID', '=', $request->ProductID)
                ->where('PurchaseID', '=', $request->PurchaseID)
                ->get();
        } else {
            $Products = ProductLedgers::all();
        }

        return response()->json($Products, 200);
    }
}
