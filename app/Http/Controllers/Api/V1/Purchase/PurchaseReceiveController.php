<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Purchase;

use ClassyPOS\Models\Accounts\General\AccountLedger;
use ClassyPOS\Models\Banks\BankBalance;
use ClassyPOS\Models\Banks\BankLedger;
use ClassyPOS\Models\Contacts\ContactBalance;
use ClassyPOS\Models\Contacts\ContactLedger;
use ClassyPOS\Models\Products\Products;
use ClassyPOS\Models\Products\ProductLedgers;
use ClassyPOS\Models\Purchase\Receive\PurchaseReceiveProductMappings;
use ClassyPOS\Models\Purchase\Receive\PurchaseReceives;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class PurchaseReceiveController extends Controller
{
    /**
     * Purchase Receive
     * Generate @Invoice
     * Update @ProductLedger
     * Update @Product
     * @return mixed
     */

    private $PurchaseInvoiceID;
    private $ReceivedProduct;
    private $ProductLedger;

    public function purchaseReceiveList()
    {
        return PurchaseReceives::where('purchase_receives.id','>',0)
            ->leftjoin('contacts', 'contacts.id', '=', 'purchase_receives.ContactID')
            ->select(
                'purchase_receives.id',
                'purchase_receives.PurchaseOrderID',
                'purchase_receives.MemoNo',
                'purchase_receives.SubTotal',
                'purchase_receives.ShippingCharge',
                'purchase_receives.TaxCharge',
                'purchase_receives.TaxCharge',
                'purchase_receives.OtherCharge',
                'purchase_receives.Total',
                'contacts.CompanyName'
            )
            ->paginate(10);

//        return PurchaseReceives::paginate(10);
    }


    public function listTrash()
    {
        // view only trashed items
        return PurchaseReceives::onlyTrashed()->paginate(10);
    }


    public function restoreTrash($PurchaseReceiveID)
    {
        // Recover soft deleted items back to list
        PurchaseReceives::withTrashed()->find($PurchaseReceiveID)->restore();
    }

    public function clearTrash($PurchaseReceiveID)
    {
        // Permanently Delete
        PurchaseReceives::withTrashed()->find($PurchaseReceiveID)->forceDelete();
    }

    public function purchaseReceiveDetails($PurchaseID)
    {
//        $Purchase = PurchaseReceives::with('contact')->where('id', '=', $PurchaseID)->first();
//        $Products = ProductLedgers::with('product')->where('PurchaseID', '=', $PurchaseID)->get();

        $Purchase = PurchaseReceives::where('id', '=', $PurchaseID)->first();

        $Products = ProductLedgers::where('PurchaseID', '=', $PurchaseID)
            ->leftjoin('products', 'products.id', '=', 'product_ledgers.ProductID')
            ->select(
                'product_ledgers.id',
                'product_ledgers.id',
                'product_ledgers.ProductID',
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
                'product_ledgers.created_at',
                'product_ledgers.updated_at',
                'products.Name'
            )
            ->get();

        $PurchaseReceiveDetails = [];
        array_push($PurchaseReceiveDetails, $Purchase);
        array_push($PurchaseReceiveDetails, $Products);

        if (!empty($PurchaseReceiveDetails)) {
            return response()->json($PurchaseReceiveDetails, 200);
        }

        return response()->json('Resource Not Found', 404);
    }


    public function purchaseReceiveStore(Request $request)
    {
        /**
         * Generate Invoice on received products.
         * And Insert received product to product_ledgers.
         */

        try {
            // Generate Invoice against received product.
            $PurchaseReceive = new PurchaseReceives();
            $PurchaseReceive->ContactID         = $request->ContactID;
            $PurchaseReceive->UserID            = $request->UserID;
            $PurchaseReceive->PurchaseOrderID   = $request->PurchaseOrderID;
            $PurchaseReceive->MemoNo            = $request->MemoNo;
            $PurchaseReceive->SubTotal          = $request->SubTotal;
            $PurchaseReceive->ShippingCharge    = $request->ShippingCharge;
            $PurchaseReceive->TaxCharge         = $request->TaxCharge;
            $PurchaseReceive->OtherCharge       = $request->OtherCharge;
            $PurchaseReceive->Total             = $request->Total;

            $PurchaseReceive->save();

            $PurchaseID = $PurchaseReceive->id;

            // Iterate all Received Product from purchase order
            $ReceivedProduct = $request->ReceivedProduct;

            for ($i = 0; $i < sizeof($ReceivedProduct); $i++) {

                // Add each product in product_ledgers table.
                $ProductLedger = new ProductLedgers();
                $ProductLedger->ProductID     = $ReceivedProduct[$i]['ProductID'];
                $ProductLedger->PurchaseID    = $PurchaseID;
                $ProductLedger->BatchNo       = $ReceivedProduct[$i]['BatchNo'];
                $ProductLedger->CustomID      = $ReceivedProduct[$i]['CustomID'];
                $ProductLedger->Model         = $ReceivedProduct[$i]['Model'];
                $ProductLedger->Description   = $ReceivedProduct[$i]['Description'];
                $ProductLedger->Qty           = $ReceivedProduct[$i]['Qty'];
                $ProductLedger->CostPrice     = $ReceivedProduct[$i]['CostPrice'];
                $ProductLedger->SalePrice     = $ReceivedProduct[$i]['SalePrice'];
                $ProductLedger->Status        = $ReceivedProduct[$i]['Status'];
                $ProductLedger->ExpiredDate   = $ReceivedProduct[$i]['ExpiredDate'];

                $ProductLedger->save();

                // Mapping Received Product.
                $ProductMapping = new PurchaseReceiveProductMappings();
                $ProductMapping->ProductID     = $ReceivedProduct[$i]['ProductID'];
                $ProductMapping->PurchaseID    = $PurchaseID;
                $ProductMapping->BatchNo       = $ReceivedProduct[$i]['BatchNo'];
                $ProductMapping->CustomID      = $ReceivedProduct[$i]['CustomID'];
                $ProductMapping->Model         = $ReceivedProduct[$i]['Model'];
                $ProductMapping->Description   = $ReceivedProduct[$i]['Description'];
                $ProductMapping->Qty           = $ReceivedProduct[$i]['Qty'];
                $ProductMapping->CostPrice     = $ReceivedProduct[$i]['CostPrice'];
                $ProductMapping->SalePrice     = $ReceivedProduct[$i]['SalePrice'];
                $ProductMapping->ExpiredDate   = $ReceivedProduct[$i]['ExpiredDate'];

                $ProductMapping->save();

                // Update Product
                $Product = Products::where('id', '=', $ReceivedProduct[$i]['ProductID'])->first();
                $Product->Qty = $Product->Qty + $ReceivedProduct[$i]['Qty'];

                $Product->save();
            }

            if ($request->IsBank == 1) {
                // Update Company Bank Transaction
                if ($request->Payment[0]['Withdraw'] > 0) {
                    $BankBalance = BankBalance::where('BankID', $request->Payment[0]['BankID'])->first();
                    $BankBalance->Balance = $BankBalance->Balance - $request->Payment[0]['Withdraw'];

                    $BankBalance->save();
                } elseif ($request->Payment[0]['Deposit'] > 0) {
                    $BankBalance = BankBalance::where('BankID', $request->Payment[0]['BankID'])->first();
                    $BankBalance->Balance = $BankBalance->Balance + $request->Payment[0]['Deposit'];

                    $BankBalance->save();
                } elseif ($request->Payment[0]['Withdraw'] > 0 && $request->Payment[0]['Deposit'] > 0) {
                    $BankBalance = BankBalance::where('BankID', $request->Payment[0]['BankID'])->first();
                    $BankBalance->Balance = $BankBalance->Balance + $request->Payment[0]['Deposit'];
                    $BankBalance->Balance = $BankBalance->Balance - $request->Payment[0]['Withdraw'];

                    $BankBalance->save();
                }

                $Balance = $BankBalance->Balance;

                $Transaction = new BankLedger();
                $Transaction->UserID            = $request->Payment[0]['UserID'];
                $Transaction->BankID            = $request->Payment[0]['BankID'];
                $Transaction->ChequeNumber      = $request->Payment[0]['ChequeNumber'];
                $Transaction->Deposit           = $request->Payment[0]['Deposit'];
                $Transaction->Withdraw          = $request->Payment[0]['Withdraw'];
                $Transaction->Balance           = $Balance;

                $Transaction->save();
            }
            $ContactDue = $request->Payment[0]['ContactCredit'] - $request->Payment[0]['ContactDebit'];

            // Update Contact Ledger
            if ($request->Payment[0]['ContactDebit'] > 0) {
                $ContactBalance = ContactBalance::where('ContactID', $request->Payment[0]['ContactID'])->first();
                $ContactBalance->Balance = $ContactBalance->Balance + $ContactDue;

                $ContactBalance->save();
            } else {
                return response()->json('M y   w h o l e   l i f e   i s   a   l i e !');
            }

            $ContactBalance = $ContactBalance->Balance;

            $ContactLedger = new ContactLedger();
            $ContactLedger->UserID              = $request->Payment[0]['UserID'];
            $ContactLedger->ContactID           = $request->Payment[0]['ContactID'];
            $ContactLedger->InvoiceID           = 0;
            $ContactLedger->PurchaseOrderID     = 0;
            $ContactLedger->Debit               = $request->Payment[0]['ContactDebit'];
            $ContactLedger->Credit              = $request->Payment[0]['ContactCredit'];
            $ContactLedger->Balance             = $ContactBalance;
            $ContactLedger->IsApproved          = 0;
            $ContactLedger->Status              = 1;

            $ContactLedger->save();

            // Update Account
//            $Balance = AccountLedger::where('AccountID', $request->Payment[0]['AccountID'])->orderBy('id', 'DESC')->first();
//            $Balance = $Balance->Balance;
//
//            $Transaction = new AccountLedger();
//            $Transaction->UserID        = $request->Payment[0]['UserID'];
//            $Transaction->AccountID     = $request->Payment[0]['AccountID'];
//            $Transaction->Description   = $request->Payment[0]['Description'];
//            $Transaction->Debit         = $request->Payment[0]['Debit'];
//            $Transaction->Credit        = $request->Payment[0]['Credit'];
//            $Transaction->Date          = $request->Payment[0]['Date'];
//
//            if ($request->Payment[0]['Debit'] > 0) {
//                $Transaction->Balance = $Balance + $request->Payment[0]['Balance'];
//            } elseif ($request->Payment[0]['Credit'] > 0) {
//                $Transaction->Balance = $Balance - $request->Payment[0]['Balance'];
//            } elseif ($request->Payment[0]['Debit'] > 0 && $request->Payment[0]['Credit'] > 0) {
//                $Transaction->Balance = $Balance + $request->Payment[0]['Balance'];
//                $Transaction->Balance = $Balance - $request->Payment[0]['Balance'];
//            } else {
//                return response('M y   w h o l e   l i f e   i s   a   L i e !', 200);
//            }
//
//            $Transaction->save();
            
            $Status = "Status: OK";
        }
        catch (\Exception $exception) {
            $Status = $exception;
        }

        return response()->json($Status, 201);
    }


    public function destroyPurchaseReceive(PurchaseReceives $PurchaseReceive)
    {
        $ProductLedger = ProductLedgers::where('PurchaseID', '=', $PurchaseReceive->id)->get();

        try {
            for ($i = 0; $i < sizeof($ProductLedger); $i++) {
                $Product = Products::where('id', '=', $ProductLedger[$i]->ProductID)->first();
                $Product->Qty = $Product->Qty - $ProductLedger[$i]->Qty;
                $Product->save();
            }

            ProductLedgers::where('PurchaseID', '=', $PurchaseReceive->id)->delete();
            PurchaseReceiveProductMappings::where('PurchaseID', '=', $PurchaseReceive->id)->delete();

            $PurchaseReceive->delete();

            $Status = 'Status: Ok';
        }
        catch (\Exception $exception) {
            $Status = $exception;
        }

        return response()->json($Status, 204);
    }

}
