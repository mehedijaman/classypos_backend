<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Sales;

use ClassyPOS\Models\Sales\Holds\SaleHoldProductMappings;
use ClassyPOS\Models\Sales\Holds\SaleHolds;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class SaleHoldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(SaleHolds::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Add entry on sale_holds
        $SaleHold = new SaleHolds();
        $SaleHold->UserID          = $request->UserID;
        $SaleHold->ShopID          = $request->ShopID;
        $SaleHold->FloorID         = $request->FloorID;
        $SaleHold->TerminalID      = $request->TerminalID;
        $SaleHold->Notes           = $request->Notes;
        $SaleHold->IsComplete      = $request->IsComplete;

        $SaleHold->save();

        $HoldID = $SaleHold->id;
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            // Map every ordered product on sale_hold_product_mappings table

            $SaleHoldProductMapping = new SaleHoldProductMappings();
            $SaleHoldProductMapping->HoldID             = $HoldID;
            $SaleHoldProductMapping->ProductLedgerID    = $Products[$i]["ProductLedgerID"];
            $SaleHoldProductMapping->Qty                = $Products[$i]["Qty"];
            $SaleHoldProductMapping->TaxID              = $Products[$i]["TaxID"];
            $SaleHoldProductMapping->SalePrice          = $Products[$i]["SalePrice"];
            $SaleHoldProductMapping->Discount           = $Products[$i]["Discount"];

            $SaleHoldProductMapping->save();
        }

        return response()->json('Status: Ok', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SaleHolds $id)
    {
        return response()->json($id, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleHolds $id)
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
