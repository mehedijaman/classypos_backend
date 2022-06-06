<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Sales;

use ClassyPOS\Models\Contacts\ContactBalance;
use ClassyPOS\Models\Contacts\ContactLedger;
use ClassyPOS\Models\Products\Products;
use ClassyPOS\Models\Products\ProductLedgers;
use ClassyPOS\Models\Sales\Invoices\SaleInvoice;
use ClassyPOS\Models\Sales\Invoices\SaleInvoiceProductMapping;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class SaleController extends Controller
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
    public function storeSale(Request $request)
    {
//        $Invoice = new SaleInvoice();
//        $Invoice->ShopID        = $request->ShopID;
//        $Invoice->FloorID       = $request->FloorID;
//        $Invoice->TerminalID    = $request->TerminalID;
//        $Invoice->UserID        = $request->UserID;
//        $Invoice->KOTID         = $request->KOTID;
//        $Invoice->OrderID       = $request->OrderID;
//        $Invoice->AdvanceID     = $request->AdvanceID;
//        $Invoice->ContactID     = $request->ContactID;
//        $Invoice->TotalCost     = $request->TotalCost;
//        $Invoice->SubTotal      = $request->SubTotal;
//        $Invoice->TaxTotal      = $request->TaxTotal;
//        $Invoice->ServiceCharge = $request->ServiceCharge;
//        $Invoice->Discount      = $request->Discount;
//        $Invoice->Total         = $request->Total;
//        $Invoice->PaidMoney     = $request->PaidMoney;
//        $Invoice->ReturnMoney   = $request->ReturnMoney;
//        $Invoice->IsVoid        = $request->IsVoid;
//        $Invoice->IsRefunded    = $request->IsRefunded;
//        $Invoice->IsPaid        = $request->IsPaid;
//        $Invoice->IsApproved    = $request->IsApproved;
//        $Invoice->Status        = $request->Status;
//        $Invoice->save();

//        $InvoiceID = $Invoice->id;
//        $Products = $request->Products;

//        for ($i = 0; $i < sizeof($Products); $i++) {
//            $ProductMapping = new SaleInvoiceProductMapping();
//            $ProductMapping->ShopID             = $Products[$i]->ShopID;
//            $ProductMapping->InvoiceID          = $InvoiceID;
//            $ProductMapping->ProductLedgerID    = $Products[$i]->ProductLedgerID;
//            $ProductMapping->Qty                = $Products[$i]->Qty;
//            $ProductMapping->CostPrice          = $Products[$i]->CostPrice;
//            $ProductMapping->SalePrice          = $Products[$i]->SalePrice;
//            $ProductMapping->TaxTotal           = $Products[$i]->TaxTotal;
//            $ProductMapping->Discount           = $Products[$i]->Discount;
//            $ProductMapping->TotalPrice         = $Products[$i]->TotalPrice;
//            $ProductMapping->save();
//
//            // Update Product ledger.
//            $ProductLedger = ProductLedgers::where('id', '=', $Products[$i]->ProductLedgerID)->first();
//            $ProductLedger->Qty = $ProductLedger->Qty - $Products[$i]->Qty;
//            $ProductLedger->save();
//
//            // Update Product.
//            $ProductID = $ProductLedger->ProductID;
//            $Product = Product::where('id', '=', $ProductID)->first();
//            $Product->Qty = $Product->Qty - $Products[$i]->Qty;
//            $Product->save();
//        }


        $Invoice = new SaleInvoice();
        $Invoice->ShopID        = $request->ShopID;
        $Invoice->FloorID       = $request->FloorID;
        $Invoice->TerminalID    = $request->TerminalID;
        $Invoice->UserID        = $request->UserID;
        $Invoice->KOTID         = $request->KOTID;
        $Invoice->OrderID       = $request->OrderID;
        $Invoice->AdvanceID     = $request->AdvanceID;
        $Invoice->ContactID     = $request->ContactID;
        $Invoice->TotalCost     = $request->TotalCost;
        $Invoice->SubTotal      = $request->SubTotal;
        $Invoice->TaxTotal      = $request->TaxTotal;
        $Invoice->ServiceCharge = $request->ServiceCharge;
        $Invoice->Discount      = $request->Discount;
        $Invoice->Total         = $request->Total;
        $Invoice->PaidMoney     = $request->PaidMoney;
        $Invoice->ReturnMoney   = $request->ReturnMoney;
        $Invoice->IsVoid        = $request->IsVoid;
        $Invoice->IsRefunded    = $request->IsRefunded;
        $Invoice->IsPaid        = $request->IsPaid;
        $Invoice->IsApproved    = $request->IsApproved;
        $Invoice->Status        = $request->Status;
        $Invoice->save();

        $InvoiceID = $Invoice->id;
        $Products = $request->Products;

        for ($i = 0; $i < sizeof($Products); $i++) {
            $ProductMapping = new SaleInvoiceProductMapping();
            $ProductMapping->ShopID             = $Products[$i]["ShopID"];
            $ProductMapping->InvoiceID          = $InvoiceID;
            $ProductMapping->ProductLedgerID    = $Products[$i]["ProductLedgerID"];
            $ProductMapping->Qty                = $Products[$i]["Qty"];
            $ProductMapping->CostPrice          = $Products[$i]["CostPrice"];
            $ProductMapping->SalePrice          = $Products[$i]["SalePrice"];
            $ProductMapping->TaxTotal           = $Products[$i]["TaxTotal"];
            $ProductMapping->Discount           = $Products[$i]["Discount"];
            $ProductMapping->TotalPrice         = $Products[$i]["TotalPrice"];
            $ProductMapping->save();

            // Update Product ledger.
            $ProductLedger = ProductLedgers::where('id', '=', $Products[$i]["ProductLedgerID"])->first();
            $ProductLedger->Qty = $ProductLedger->Qty - $Products[$i]["Qty"];
            $ProductLedger->save();

            // Update Product.
//            $ProductID = $ProductLedger->ProductID;
//            $Product = Products::where('id', '=', $ProductID)->first();
//            $Product->Qty = $Product->Qty - $Products[$i]['Qty'];
//            $Product->save();
        }

        // Update Contact Ledger
        $ContactBalance = ContactBalance::where('ContactID', $request->ContactID)->first();

        if ($request->Total > 0) {
            $ContactBalance->Balance = $ContactBalance->Balance + $request->Total;
        }
        if ($request->PaidMoney > 0) {
            $ContactBalance->Balance = $ContactBalance->Balance - $request->PaidMoney;
        }

        $ContactBalance->save();

        $ContactBalance = $ContactBalance->Balance;

        $ContactLedger = new ContactLedger();
        $ContactLedger->UserID              = $request->UserID;
        $ContactLedger->ContactID           = $request->ContactID;
        $ContactLedger->InvoiceID           = $InvoiceID;
        $ContactLedger->Debit               = $request->Total;
        $ContactLedger->Credit              = $request->PaidMoney;
        $ContactLedger->Balance             = $ContactBalance;
        $ContactLedger->PaymentMethod       = $request->PaymentMethod;
        $ContactLedger->Notes               = $request->Notes;
        $ContactLedger->DueDate             = $request->DueDate;
        $ContactLedger->PaymentDate         = $request->PaymentDate;
        $ContactLedger->IsApproved          = $request->IsApproved;
        $ContactLedger->Status              = $request->Status;

        $ContactLedger->save();

        return response()->json(null, 201);
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
