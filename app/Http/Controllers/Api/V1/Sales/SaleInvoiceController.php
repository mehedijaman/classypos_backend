<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Sales;

use ClassyPOS\Models\Sales\Invoices\SaleInvoice;
use ClassyPOS\Models\Sales\Invoices\SaleInvoiceProductMapping;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SaleInvoice::paginate(15);
    }

    public function filterInvoice($IsPaid, $from, $to)
    {
        $invoiceList = new SaleInvoice();
        return $invoiceList->invoiceList($IsPaid, $to, $from);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $SaleInvoice = new SaleInvoice();
        $SaleInvoice->UserID        = $request->UserID;
        $SaleInvoice->ShopID        = $request->ShopID;
        $SaleInvoice->FloorID       = $request->FloorID;
        $SaleInvoice->TerminalID    = $request->TerminalID;
        $SaleInvoice->KOTID         = $request->KOTID;
        $SaleInvoice->OrderID       = $request->OrderID;
        $SaleInvoice->AdvanceID     = $request->AdvanceID;
        $SaleInvoice->ContactID     = $request->ContactID;
        $SaleInvoice->TotalCost     = $request->TotalCost;
        $SaleInvoice->SubTotal      = $request->SubTotal;
        $SaleInvoice->TaxTotal      = $request->TaxTotal;
        $SaleInvoice->ServiceCharge = $request->ServiceCharge;
        $SaleInvoice->Discount      = $request->Discount;
        $SaleInvoice->Total         = $request->Total;
        $SaleInvoice->PaidMoney     = $request->PaidMoney;
        $SaleInvoice->ReturnMoney   = $request->ReturnMoney;
        $SaleInvoice->IsVoid        = $request->IsVoid;
        $SaleInvoice->IsRefunded    = $request->IsRefunded;
        $SaleInvoice->IsPaid        = $request->IsPaid;
        $SaleInvoice->IsApproved    = $request->IsApproved;
        $SaleInvoice->Status        = $request->Status;

        $SaleInvoice->save();

        $InvoiceID = $SaleInvoice->id;
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            $SaleInvoiceProductMapping = new SaleInvoiceProductMapping();
            $SaleInvoiceProductMapping->ShopID          = $Products[$i]['ShopID'];
            $SaleInvoiceProductMapping->InvoiceID       = $InvoiceID;
            $SaleInvoiceProductMapping->ProductLedgerID = $Products[$i]['ProductLedgerID'];
            $SaleInvoiceProductMapping->Qty             = $Products[$i]['Qty'];
            $SaleInvoiceProductMapping->CostPrice       = $Products[$i]['CostPrice'];
            $SaleInvoiceProductMapping->SalePrice       = $Products[$i]['SalePrice'];
            $SaleInvoiceProductMapping->TaxTotal        = $Products[$i]['TaxTotal'];
            $SaleInvoiceProductMapping->Discount        = $Products[$i]['Discount'];
            $SaleInvoiceProductMapping->TotalPrice      = $Products[$i]['TotalPrice'];

            $SaleInvoiceProductMapping->save();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return response()->json('Status: Ok', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SaleInvoice $id)
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
    public function listTrash()
    {
        // view only trashed items
        return SaleInvoice::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($id)
    {
        // Recover soft deleted items back to list
        SaleInvoice::withTrashed()->find($id)->restore();
    }

    public function clearTrash($id)
    {
        // Permanently Delete
        SaleInvoice::withTrashed()->find($id)->forceDelete();
    }
}
