<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Sales;

use ClassyPOS\Models\Sales\Quote\SaleQuote;
use ClassyPOS\Models\Sales\Quote\SaleQuoteProductMapping;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class SaleQuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(SaleQuote::all(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $SaleQuoteID
     * @return \Illuminate\Http\Response
     */
    public function listTrash()
    {
        // view only trashed items
        return SaleQuote::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($SaleQuoteID)
    {
        // Recover soft deleted items back to list
        SaleQuote::withTrashed()->find($SaleQuoteID)->restore();
    }

    public function clearTrash($SaleQuoteID)
    {
        // Permanently Delete
        SaleQuote::withTrashed()->find($SaleQuoteID)->forceDelete();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Add entry on sale_quotes
        $SaleQuote = new SaleQuote();
        $SaleQuote->UserID          = $request->UserID;
        $SaleQuote->ShopID          = $request->ShopID;
        $SaleQuote->FloorID         = $request->FloorID;
        $SaleQuote->TerminalID      = $request->TerminalID;
        $SaleQuote->ContactID       = $request->ContactID;
        $SaleQuote->Title           = $request->Title;
        $SaleQuote->SubTotal        = $request->SubTotal;
        $SaleQuote->TaxTotal        = $request->TaxTotal;
        $SaleQuote->ShippingCharge  = $request->ShippingCharge;
        $SaleQuote->PackagingCharge = $request->PackagingCharge;
        $SaleQuote->OtherCharge     = $request->OtherCharge;
        $SaleQuote->Discount        = $request->Discount;
        $SaleQuote->Total           = $request->Total;
        $SaleQuote->Notes           = $request->Notes;
        $SaleQuote->Status          = $request->Status;
        $SaleQuote->ExpiredDate     = $request->ExpiredDate;

        $SaleQuote->save();

        $QuoteID = $SaleQuote->id;
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            // Map every quoted product on sale_quote_product_mappings table

            $SaleQuoteProductMapping = new SaleQuoteProductMapping();
            $SaleQuoteProductMapping->QuoteID           = $QuoteID;
            $SaleQuoteProductMapping->ProductLedgerID   = $Products[$i]["ProductLedgerID"];
            $SaleQuoteProductMapping->Price             = $Products[$i]["Price"];
            $SaleQuoteProductMapping->Qty               = $Products[$i]["Qty"];
            $SaleQuoteProductMapping->TaxTotal          = $Products[$i]["TaxTotal"];
            $SaleQuoteProductMapping->ShippingCharge    = $Products[$i]["ShippingCharge"];
            $SaleQuoteProductMapping->PackagingCharge   = $Products[$i]["PackagingCharge"];
            $SaleQuoteProductMapping->OtherCharge       = $Products[$i]["OtherCharge"];

            $SaleQuoteProductMapping->save();
        }

        return response()->json('Status: Ok', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $SaleQuoteID
     * @return \Illuminate\Http\Response
     */
    public function show(SaleQuote $SaleQuoteID)
    {
        $Products = SaleQuoteProductMapping::select(
            'sale_quote_product_mappings.QuoteID',

            'sale_quote_product_mappings.ProductLedgerID',
            'product_ledgers.ProductID',
            'products.Name',

            'sale_quote_product_mappings.Qty',
            'sale_quote_product_mappings.Price',
            'sale_quote_product_mappings.TaxTotal',
            'sale_quote_product_mappings.ShippingCharge',
            'sale_quote_product_mappings.PackagingCharge',
            'sale_quote_product_mappings.OtherCharge',
            'sale_quote_product_mappings.Discount',
            'sale_quote_product_mappings.Total'
        )
            ->where('QuoteID', '=', $SaleQuoteID->id)
            ->leftJoin('product_ledgers', 'product_ledgers.id', 'sale_quote_product_mappings.ProductLedgerID')
            ->leftJoin('products', 'products.id', 'product_ledgers.ProductID')
            ->WhereNull('sale_quote_product_mappings.deleted_at')
            ->get();

        $SaleQuoteID['Products'] = $Products;

        return response()->json($SaleQuoteID, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $SaleQuoteID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleQuote $SaleQuoteID)
    {
        $SaleQuoteID->update($request->all());

        return response()->json($SaleQuoteID, 200);
    }
}
