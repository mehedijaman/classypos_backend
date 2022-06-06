<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Purchase;

use ClassyPOS\Models\Purchase\Order\PurchaseOrders;
use ClassyPOS\Models\Purchase\Order\PurchaseOrderProductMappings;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
    /**
     * Purchase order manipulation
     * Take order generate invoice
     * and make inventory ledger
     * return @void
     *
     * */


    private $ContactID;
    private $ReferenceID;
    private $IsApproved;
    private $IsDelivered;
    private $IsBilled;
    private $Status;
    private $DeliveryAddress;
    private $DeliveryDate;
    private $SubTotal;
    private $ShippingCharge;
    private $TaxCharge;
    private $OtherCharge;
    private $Total;


    public function listPurchaseOrder()
    {
        return PurchaseOrders::where('purchase_orders.id','>',0)
            ->leftjoin('contacts','contacts.id','=','purchase_orders.ContactID')
            ->select(
                'purchase_orders.id',
                'purchase_orders.UserID',
                'purchase_orders.ReferenceNo',
                'purchase_orders.IsApproved',
                'purchase_orders.IsDelivered',
                'purchase_orders.IsBilled',
                'purchase_orders.Status',
                'purchase_orders.Notes',
                'purchase_orders.DeliveryAddress',
                'purchase_orders.DeliveryDate',
                'purchase_orders.SubTotal',
                'purchase_orders.ShippingCharge',
                'purchase_orders.TaxCharge',
                'purchase_orders.OtherCharge',
                'purchase_orders.Total',
                'contacts.CompanyName',
                'purchase_orders.created_at',
                'purchase_orders.updated_at'
            )
            ->paginate(10);

        // return PurchaseOrders::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return PurchaseOrders::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($PurchaseOrderID)
    {
        // Recover soft deleted items back to list
        PurchaseOrders::withTrashed()->find($PurchaseOrderID)->restore();
    }


    public function clearTrash($PurchaseOrderID)
    {
        // Permanently Delete
        PurchaseOrders::withTrashed()->find($PurchaseOrderID)->forceDelete();
    }


    public function detailsPurchaseOrder(PurchaseOrders $PurchaseOrder)
    {
        // View specific Purchase Order @param PurchaseOrder $PurchaseOrder.        
        $PurchaseOrderInformation = PurchaseOrders::where('purchase_orders.id', '=', $PurchaseOrder->id)
            ->leftjoin('contacts', 'contacts.id', '=', 'purchase_orders.ContactID')
            ->select(
                'purchase_orders.created_at',
                'purchase_orders.DeliveryAddress',
                'purchase_orders.DeliveryDate',
                'purchase_orders.TaxCharge',
                'purchase_orders.ShippingCharge',
                'purchase_orders.OtherCharge',
                'purchase_orders.SubTotal',
                'purchase_orders.Total',
                'purchase_orders.Notes',
                'purchase_orders.ReferenceNo',
                'contacts.CompanyName',
                'purchase_orders.ContactID'
            )
            ->first();

        // View specific Purchase Order's Product Items.
        $Products = PurchaseOrderProductMappings::where('PurchaseOrderID', '=', $PurchaseOrder->id)
            ->leftjoin('products', 'products.id', '=', 'purchase_order_product_mappings.ProductID')
            ->select(
                'purchase_order_product_mappings.ProductID',
                'purchase_order_product_mappings.Price',
                'purchase_order_product_mappings.Qty',
                'products.Name',
                'products.CategoryID'
            )
            ->get();

        $OrderDetails = [];
        array_push($OrderDetails, $PurchaseOrderInformation);
        array_push($OrderDetails, $Products);

        if (!empty($PurchaseOrder)) {
            return response()->json($OrderDetails, 200);
        }

        return response()->json('Resource Not Found', 404);
    }


    public function storePurchaseOrder(Request $request)
    {
        /**
         * Generate Purchase Order to get
         * PurchaseOrderID
         */
        $PurchaseOrder = new PurchaseOrders();
        $PurchaseOrder->UserID          = $request->UserID;
        $PurchaseOrder->ContactID       = $request->ContactID;
        $PurchaseOrder->ReferenceNo     = $request->ReferenceNo;
        $PurchaseOrder->IsApproved      = $request->IsApproved;
        $PurchaseOrder->IsDelivered     = $request->IsDelivered;
        $PurchaseOrder->IsBilled        = $request->IsBilled;
        $PurchaseOrder->Status          = $request->Status;
        $PurchaseOrder->DeliveryAddress = $request->DeliveryAddress;
        $PurchaseOrder->DeliveryDate    = $request->DeliveryDate;
        $PurchaseOrder->Notes           = $request->Notes;
        $PurchaseOrder->SubTotal        = $request->SubTotalPrice;
        $PurchaseOrder->ShippingCharge  = $request->ShippingCharge;
        $PurchaseOrder->TaxCharge       = $request->TaxCharge;
        $PurchaseOrder->OtherCharge     = $request->OtherCharge;
        $PurchaseOrder->Total           = $request->TotalPrice;
        $PurchaseOrder->save();

        $PurchaseOrderID    = $PurchaseOrder->id;

        // Iterate all Ordered Product & Store Product wise.
        $OrderedProducts = $request->OrderedProducts;

        // Add each ordered product in purchase_order_product_mapping table.
        for ($i=0; $i < sizeof($OrderedProducts); $i++) {
            $PurchaseOrderProductMappings = new PurchaseOrderProductMappings();
            $PurchaseOrderProductMappings->PurchaseOrderID    = $PurchaseOrderID;
            $PurchaseOrderProductMappings->ProductID          = $OrderedProducts[$i]['ProductID'];
            $PurchaseOrderProductMappings->Description        = $OrderedProducts[$i]['Description'];
            $PurchaseOrderProductMappings->Model              = $OrderedProducts[$i]['Model'];
            $PurchaseOrderProductMappings->Price              = $OrderedProducts[$i]['Price'];
            $PurchaseOrderProductMappings->Qty                = $OrderedProducts[$i]['Qty'];

            $PurchaseOrderProductMappings->save();
        }

        return response()->json('Status: Ok', 201);
    }


    public function updatePurchaseOrder(Request $request, PurchaseOrders $PurchaseOrder)
    {
        $PurchaseOrder->update($request->all());

        return response()->json($PurchaseOrder, 200);
    }


    public function destroyPurchaseOrder(PurchaseOrders $PurchaseOrder)
    {
        try {
            PurchaseOrderProductMappings::where('PurchaseOrderID', '=', $PurchaseOrder->id)->delete();
            $PurchaseOrder->delete();

            $Status = "Status: Ok";
        }
        catch (\Exception $exception) {
            $Status = $exception;
        }

        return response()->json($Status, 204);
    }

}
