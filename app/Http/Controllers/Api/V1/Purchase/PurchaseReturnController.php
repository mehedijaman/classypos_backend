<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Purchase;

use ClassyPOS\Models\Products\Products;
use ClassyPOS\Models\Products\ProductLedgers;
use ClassyPOS\Models\Purchase\Returns\PurchaseReturnProductMappings;
use ClassyPOS\Models\Purchase\Returns\PurchaseReturns;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class PurchaseReturnController extends Controller
{
    public function purchaseReturnList()
    {
        return PurchaseReturns::where('purchase_returns.id','>',0)
            ->leftjoin('contacts', 'contacts.id', '=', 'purchase_returns.ContactID')
            ->select(
                'purchase_returns.id',
                'purchase_returns.UserID',
                'purchase_returns.ShopID',
                'purchase_returns.PurchaseID',
                'purchase_returns.MemoNo',
                'purchase_returns.Total',
                'purchase_returns.ReturnReason',
                'contacts.CompanyName',
                'purchase_returns.created_at',
                'purchase_returns.updated_at'
            )
            ->paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return PurchaseReturns::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($PurchaseReturnID)
    {
        // Recover soft deleted items back to list
        PurchaseReturns::withTrashed()->find($PurchaseReturnID)->restore();
    }

    public function clearTrash($PurchaseReturnID)
    {
        // Permanently Delete
        PurchaseReturns::withTrashed()->find($PurchaseReturnID)->forceDelete();
    }


    public function purchaseReturnDetails(PurchaseReturns $PurchaseReturn)
    {
        $PurchaseReturnDetails = [];

        $Products = PurchaseReturnProductMappings::where('PRID', '=', $PurchaseReturn->id)->get();
        array_push($PurchaseReturnDetails, $PurchaseReturn);
        array_push($PurchaseReturnDetails, $Products);

        if (!empty($PurchaseReturn)) {
            return response()->json($PurchaseReturnDetails, 200);
        }

        return response()->json('Resource Not Found', 404);
    }


    public function purchaseReturnStore(Request $request)
    {
        // Return purchased products.
        $PurchaseReturn = new PurchaseReturns();
        $PurchaseReturn->ShopID             = $request->ShopID;
        $PurchaseReturn->ContactID          = $request->ContactID;
        $PurchaseReturn->UserID             = $request->UserID;
        $PurchaseReturn->PurchaseID         = $request->PurchaseID;
        $PurchaseReturn->MemoNo             = $request->MemoNo;
        $PurchaseReturn->Total              = $request->Total;
        $PurchaseReturn->ReturnReason       = $request->ReturnReason;

        $PurchaseReturn->save();

        $PRID = $PurchaseReturn->id;

        // Iterate all Returned Product
        $ReturnedProducts = $request->ReturnedProducts;

        for ($i = 0; $i < sizeof($ReturnedProducts); $i++) {

            $PurchaseReturnProductMapping = new PurchaseReturnProductMappings();
            $PurchaseReturnProductMapping->PRID             = $PRID;
            $PurchaseReturnProductMapping->ProductLedgerID  = $ReturnedProducts[$i]['ProductLedgerID'];
            $PurchaseReturnProductMapping->Qty              = $ReturnedProducts[$i]['Qty'];
            $PurchaseReturnProductMapping->Price            = $ReturnedProducts[$i]['Price'];

            $PurchaseReturnProductMapping->save();

            // Less each product from product_ledgers table.
            $Product = Products::where('id', '=', $ReturnedProducts[$i]['ProductID'])->first();
            $Product->Qty = $Product->Qty - $ReturnedProducts[$i]['Qty'];

            $Product->save();

            // Less each product from products table.
            $ProductLedger = ProductLedgers::where('id', '=', $ReturnedProducts[$i]['ProductLedgerID'])->first();
            $ProductLedger->Qty = $ProductLedger->Qty - $ReturnedProducts[$i]['Qty'];

            $ProductLedger->save();
        }

        return response()->json('Status: OK', 201);
    }


    public function destroyPurchaseReturn(PurchaseReturns $PurchaseReturn)
    {
        $Products = PurchaseReturnProductMappings::where('PRID', '=', $PurchaseReturn->id)->get();

        for ($j = 0; $j < count($Products); $j++) {
            $Product = Products::where('id', '=', $Products[$j]->ProductID)->first();
            $Product->Qty = $Product->Qty + $Products[$j]->Qty;

            $Product->save();

            $ProductLedger = ProductLedgers::where('id', '=', $Products[$j]->LedgerID)->first();
            $ProductLedger->Qty = $ProductLedger->Qty + $Products[$j]->Qty;

            $ProductLedger->save();
        }

        PurchaseReturnProductMappings::where('PRID', '=', $PurchaseReturn->id)->delete();
        $PurchaseReturn->delete();

        return response()->json(null, 200);
    }

}
