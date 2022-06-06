<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Shops;

use ClassyPOS\Models\Shops\ShopInvoiceSettings;
use ClassyPOS\Models\Shops\Shops;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ShopController extends Controller
{
    /**
     * Shop manipulation
     * Create, Update, Find Shop
     * return @void
     *
     * */

    public function listShop()
    {
        return Shops::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return Shops::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ShopID)
    {
        // Recover soft deleted items back to list
        Shops::withTrashed()->find($ShopID)->restore();
    }

    public function clearTrash($ShopID)
    {
        // Permanently Delete
        Shops::withTrashed()->find($ShopID)->forceDelete();
    }


    public function showShop(Shops $ShopID)
    {
        // View specific Shop @param Shop $ShopID.

        return response()->json($ShopID, 200);
    }

    public function storeShop(Request $request)
    {
        $Shop = new Shops();
        $Shop->Name         = $request->Name;
        $Shop->LegalName    = $request->LegalName;
        $Shop->Type         = $request->Type;
        $Shop->Address      = $request->Address;
        $Shop->City         = $request->City;
        $Shop['Province']     = $request['Province'];
        $Shop->Phone        = $request->Phone;
        $Shop->Email        = $request->Email;
        $Shop->Website      = $request->Website;
        $Shop->Logo         = $request->Logo;
        $Shop->LicenceNo    = $request->LicenceNo;
        $Shop->VatRegNo     = $request->VatRegNo;
        $Shop->Notes        = $request->Notes;

        $Shop->save();

        $ShopInvoiceSetting = new ShopInvoiceSettings();
        $ShopInvoiceSetting->ShopID                 = $request->ShopID;
        $ShopInvoiceSetting->FloorID                = $request->FloorID;
        $ShopInvoiceSetting->TerminalID             = $request->TerminalID;
        $ShopInvoiceSetting->Header                 = $request->Header;
        $ShopInvoiceSetting->Footer                 = $request->Footer;
        $ShopInvoiceSetting->ShowDiscount           = $request->ShowDiscount;
        $ShopInvoiceSetting->ShowPhone              = $request->ShowPhone;
        $ShopInvoiceSetting->ShowInvoiceID          = $request->ShowInvoiceID;
        $ShopInvoiceSetting->ShowVatReg             = $request->ShowVatReg;
        $ShopInvoiceSetting->ShowProductID          = $request->ShowProductID;
        $ShopInvoiceSetting->ShowTotalQty           = $request->ShowTotalQty;
        $ShopInvoiceSetting->ShowHeader             = $request->ShowHeader;
        $ShopInvoiceSetting->ShowFooter             = $request->ShowFooter;
        $ShopInvoiceSetting->ShowLogo               = $request->ShowLogo;
        $ShopInvoiceSetting->ShowTax                = $request->ShowTax;
        $ShopInvoiceSetting->ShowAddress            = $request->ShowAddress;
        $ShopInvoiceSetting->ShowEmail              = $request->ShowEmail;
        $ShopInvoiceSetting->SaleInvoiceSize        = $request->SaleInvoiceSize;
        $ShopInvoiceSetting->PurchaseInvoiceSize    = $request->PurchaseInvoiceSize;
        $ShopInvoiceSetting->FontSize               = $request->FontSize;
        $ShopInvoiceSetting->FontFamily             = $request->FontFamily;
        $ShopInvoiceSetting->LogoWidth              = $request->LogoWidth;
        $ShopInvoiceSetting->LogoHeight             = $request->LogoHeight;

        $ShopInvoiceSetting->save();

        return response()->json($Shop, 201);
    }

    public function updateShop(Request $request, Shops $ShopID)
    {
        $ShopID->Name         = $request->Name;
        $ShopID->LegalName    = $request->LegalName;
        $ShopID->Type         = $request->Type;
        $ShopID->Address      = $request->Address;
        $ShopID->City         = $request->City;
        $ShopID->Province     = $request->Province;
        $ShopID->Phone        = $request->Phone;
        $ShopID->Email        = $request->Email;
        $ShopID->Website      = $request->Website;
        $ShopID->Logo         = $request->Logo;
        $ShopID->LicenceNo    = $request->LicenceNo;
        $ShopID->VatRegNo     = $request->VatRegNo;
        $ShopID->Notes        = $request->Notes;

        $ShopID->save();

        return response()->json($ShopID, 200);
    }

    public function updateInvoiceSetting(Request $request)
    {
        $ShopInvoiceSetting = ShopInvoiceSettings::where('ShopID', $request->ShopID)->first();
        $ShopInvoiceSetting->ShopID                 = $request->ShopID;
        $ShopInvoiceSetting->FloorID                = $request->FloorID;
        $ShopInvoiceSetting->TerminalID             = $request->TerminalID;
        $ShopInvoiceSetting->Header                 = $request->Header;
        $ShopInvoiceSetting->Footer                 = $request->Footer;
        $ShopInvoiceSetting->ShowDiscount           = $request->ShowDiscount;
        $ShopInvoiceSetting->ShowPhone              = $request->ShowPhone;
        $ShopInvoiceSetting->ShowInvoiceID          = $request->ShowInvoiceID;
        $ShopInvoiceSetting->ShowVatReg             = $request->ShowVatReg;
        $ShopInvoiceSetting->ShowProductID          = $request->ShowProductID;
        $ShopInvoiceSetting->ShowTotalQty           = $request->ShowTotalQty;
        $ShopInvoiceSetting->ShowHeader             = $request->ShowHeader;
        $ShopInvoiceSetting->ShowFooter             = $request->ShowFooter;
        $ShopInvoiceSetting->ShowLogo               = $request->ShowLogo;
        $ShopInvoiceSetting->ShowTax                = $request->ShowTax;
        $ShopInvoiceSetting->ShowAddress            = $request->ShowAddress;
        $ShopInvoiceSetting->ShowEmail              = $request->ShowEmail;
        $ShopInvoiceSetting->SaleInvoiceSize        = $request->SaleInvoiceSize;
        $ShopInvoiceSetting->PurchaseInvoiceSize    = $request->PurchaseInvoiceSize;
        $ShopInvoiceSetting->FontSize               = $request->FontSize;
        $ShopInvoiceSetting->FontFamily             = $request->FontFamily;
        $ShopInvoiceSetting->LogoWidth              = $request->LogoWidth;
        $ShopInvoiceSetting->LogoHeight             = $request->LogoHeight;

        $ShopInvoiceSetting->save();

        return response()->json($ShopInvoiceSetting, 200);
    }

    public function destroyShop(Shops $ShopID)
    {
        $ShopID->delete();

        return response()->json(null, 204);
    }
}
