<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Sales;

use ClassyPOS\Models\Sales\Orders\SaleOrders;
use ClassyPOS\Models\Sales\Orders\SaleOrderProductMappings;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(SaleOrders::all(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $SaleOrdersID
     * @return \Illuminate\Http\Response
     */
    public function listTrash()
    {
        // view only trashed items
        return SaleOrders::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($SaleOrderID)
    {
        // Recover soft deleted items back to list
        SaleOrders::withTrashed()->find($SaleOrderID)->restore();
    }

    public function clearTrash($SaleOrderID)
    {
        // Permanently Delete
        SaleOrders::withTrashed()->find($SaleOrderID)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Add entry on sale_orders
        $SaleOrder = new SaleOrders();
        $SaleOrder->UserID          = $request->UserID;
        $SaleOrder->ShopID          = $request->ShopID;
        $SaleOrder->FloorID         = $request->FloorID;
        $SaleOrder->TerminalID      = $request->TerminalID;
        $SaleOrder->ContactID       = $request->ContactID;
        $SaleOrder->ReferenceNo     = $request->ReferenceNo;
        $SaleOrder->Name            = $request->Name;
        $SaleOrder->Email           = $request->Email;
        $SaleOrder->Phone           = $request->Phone;
        $SaleOrder->Address         = $request->Address;
        $SaleOrder->SubTotal        = $request->SubTotal;
        $SaleOrder->TaxTotal        = $request->TaxTotal;
        $SaleOrder->ShippingCharge  = $request->ShippingCharge;
        $SaleOrder->OtherCharge     = $request->OtherCharge;
        $SaleOrder->Discount        = $request->Discount;
        $SaleOrder->Total           = $request->Total;
        $SaleOrder->AdvancePaid     = $request->AdvancePaid;
        $SaleOrder->Due             = $request->Due;
        $SaleOrder->IsConfirmed     = $request->IsConfirmed;
        $SaleOrder->IsDelivered     = $request->IsDelivered;
        $SaleOrder->OrderDate       = $request->OrderDate;
        $SaleOrder->DeliveryDate    = $request->DeliveryDate;

        $SaleOrder->save();

        $OrderID = $SaleOrder->id;
        $Products = $request->Products;

        for ($i=0; $i<sizeof($Products); $i++) {
            // Map every ordered product on sale_order_product_mappings table

            $SaleOrderProductMapping = new SaleOrderProductMappings();
            $SaleOrderProductMapping->OrderID           = $OrderID;
            $SaleOrderProductMapping->ProductLedgerID   = $Products[$i]["ProductLedgerID"];
            $SaleOrderProductMapping->Price             = $Products[$i]["Price"];
            $SaleOrderProductMapping->Qty               = $Products[$i]["Qty"];
            $SaleOrderProductMapping->Status            = $Products[$i]["Status"];

            $SaleOrderProductMapping->save();
        }

        return response()->json('Status: Ok', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SaleOrders $SaleOrderID)
    {
        $Products = SaleOrderProductMappings::select(
            'sale_order_product_mappings.OrderID',

            'sale_order_product_mappings.ProductLedgerID',
            'product_ledgers.ProductID',
            'products.Name',

            'sale_order_product_mappings.Price',
            'sale_order_product_mappings.Qty',
            'sale_order_product_mappings.Status'
        )
            ->where('OrderID', '=', $SaleOrderID->id)
            ->leftJoin('product_ledgers', 'product_ledgers.id', 'sale_order_product_mappings.ProductLedgerID')
            ->leftJoin('products', 'products.id', 'product_ledgers.ProductID')
            ->WhereNull('sale_order_product_mappings.deleted_at')
            ->get();

        $SaleOrderID['Products'] = $Products;

        return response()->json($SaleOrderID, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $SaleOrderID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleOrders $SaleOrderID)
    {
        $SaleOrderID->update($request->all());

        return response()->json($SaleOrderID, 200);
    }
}
