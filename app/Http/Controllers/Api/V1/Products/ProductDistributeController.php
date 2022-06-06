<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductLedgers;
use ClassyPOS\Models\Products\Products;
use ClassyPOS\Models\Shops\ShopProductLedgers;
use ClassyPOS\Models\Shops\ShopProductStocks;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductDistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function distributeProduct(Request $request)
    {
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            // If already the product have in shop_stock
            // Update the quantity.
            // If not
            // on shop_stock add the product.
            $Product = ShopProductStocks::where('ProductLedgerID', '=', $Products[$i]['ProductLedgerID'])->first();

            if (empty($Product)) {
                $ShopProductStock = new ShopProductStocks();
                $ShopProductStock->ShopID           = $request->ShopID;
                $ShopProductStock->FloorID          = $request->FloorID;
                $ShopProductStock->TerminalID       = $request->TerminalID;
                $ShopProductStock->ProductLedgerID  = $Products[$i]['ProductLedgerID'];
                $ShopProductStock->Qty              = $Products[$i]['Qty'];
                $ShopProductStock->Status           = $request->Status;

                $ShopProductStock->save();
            }
            else {
                $Product->Qty = $Product->Qty + $Products[$i]['Qty'];
                $Product->save();
            }

            // If already the product have in shop_ledger
            // Update the quantity.
            // If not
            // on shop_ledger add the product.
            $ShopProductLedger = new ShopProductLedgers();
            $ShopProductLedger->UserID          = $request->ShopID;
            $ShopProductLedger->ShopID          = $request->ShopID;
            $ShopProductLedger->FloorID         = $request->FloorID;
            $ShopProductLedger->TerminalID      = $request->TerminalID;
            $ShopProductLedger->ProductLedgerID = $Products[$i]['ProductLedgerID'];
            $ShopProductLedger->Qty             = $Products[$i]['Qty'];
            $ShopProductLedger->Notes           = $request->Notes;

            $ShopProductLedger->save();


            // After all we need to less the product from
            // Our main product_ledger nah! :)
            $Product = ProductLedgers::where('id', '=', $Products[$i]['ProductLedgerID'])->firstOrFail();
            $Product->Qty = $Product->Qty - $Products[$i]['Qty'];
            $Product->save();

            // Also we need to less the product from
            // Our main product nah! :)
            $Product = Products::where('id', '=', $Product->ProductID)->firstOrFail();
            $Product->Qty = $Product->Qty - $Products[$i]['Qty'];
            $Product->save();
        }

        return response()->json('Status: Ok', 201);
    }

    public function returnToWareHouse(Request $request)
    {
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            // On return to Warehouse
            // Update shop_product_stock
            $Product = ShopProductStocks::where('ProductLedgerID', '=', $Products[$i]['ProductLedgerID'])->first();
            $Product->Qty = $Product->Qty - $Products[$i]['Qty'];
            $Product->save();

            // Then we need to
            // Update shop_product_ledger
            $Product = ShopProductLedgers::where('ProductLedgerID', '=', $Products[$i]['ProductLedgerID'])->first();
            $Product->delete();

            // After all we need to add the product to
            // Our main product_ledger nah! :)
            $Product = ProductLedgers::where('id', '=', $Products[$i]['ProductLedgerID'])->firstOrFail();
            $Product->Qty = $Product->Qty + $Products[$i]['Qty'];
            $Product->save();
        }

        return response()->json('Status: Ok', 201);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
