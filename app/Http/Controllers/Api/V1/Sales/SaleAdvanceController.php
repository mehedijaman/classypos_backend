<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Sales;

use ClassyPOS\Models\Sales\Advances\SaleAdvanceProductMappings;
use ClassyPOS\Models\Sales\Advances\SaleAdvances;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class SaleAdvanceController extends Controller
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
        // Add entry on sale_advances
        $SaleAdvance = new SaleAdvances();
        $SaleAdvance->UserID          = $request->UserID;
        $SaleAdvance->ShopID          = $request->ShopID;
        $SaleAdvance->FloorID         = $request->FloorID;
        $SaleAdvance->TerminalID      = $request->TerminalID;
        $SaleAdvance->ContactID       = $request->ContactID;
        $SaleAdvance->Name            = $request->Name;
        $SaleAdvance->Email           = $request->Email;
        $SaleAdvance->Phone           = $request->Phone;
        $SaleAdvance->Address         = $request->Address;
        $SaleAdvance->Amount          = $request->Amount;
        $SaleAdvance->Due             = $request->Due;
        $SaleAdvance->Notes           = $request->Notes;
        $SaleAdvance->DeliveryDate    = $request->DeliveryDate;
        $SaleAdvance->Status          = $request->Status;

        $SaleAdvance->save();

        $AdvanceID = $SaleAdvance->id;
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            // Map every ordered product on sale_advance_product_mappings table

            $SaleAdvanceProductMapping = new SaleAdvanceProductMappings();
            $SaleAdvanceProductMapping->AdvanceID           = $AdvanceID;
            $SaleAdvanceProductMapping->ProductLedgerID     = $Products[$i]["ProductLedgerID"];
            $SaleAdvanceProductMapping->Qty                 = $Products[$i]["Qty"];
            $SaleAdvanceProductMapping->TaxID               = $Products[$i]["TaxID"];
            $SaleAdvanceProductMapping->SalePrice           = $Products[$i]["SalePrice"];
            $SaleAdvanceProductMapping->Discount            = $Products[$i]["Discount"];

            $SaleAdvanceProductMapping->save();
        }

        return response()->json('Status: Ok', 201);
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
