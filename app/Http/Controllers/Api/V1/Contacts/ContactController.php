<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Contacts;

use ClassyPOS\Models\Banks\BankLedger;
use ClassyPOS\Models\Contacts\ContactBalance;
use ClassyPOS\Models\Contacts\ContactLedger;
use ClassyPOS\Models\Contacts\Contacts;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Contact manipulation
     * Create, Update, Find Contact
     * return @void
     *
     * */

    public function listContact()
    {
        return Contacts::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return Contacts::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ContactID)
    {
        // Recover soft deleted items back to list
        Contacts::withTrashed()->find($ContactID)->restore();
    }

    public function clearTrash($ContactID)
    {
        // Permanently Delete
        Contacts::withTrashed()->find($ContactID)->forceDelete();
    }


    public function showContact(Contacts $ContactID)
    {
        // View specific Contact @param Contact $ContactID.

        return response()->json($ContactID, 200);
    }

    public function storeContact(Request $request)
    {
        if($request->file('file') == "")
        {
            $ImageName = "No Image";
        }
        else
        {
            // retrieve original file path
            $ImageTempName = $request->file('file')->getPathName();
            //retrieve original file name
            $ImageName = $request->file('file')->getClientOriginalName();
            // define path to upload image
            $Path = base_path() . '/public/uploads/image/contact';
            // upload image to defined path directory
            $request->file('file')->move($Path , $ImageName);

            $ImageName = $request->file('file')->getClientOriginalName();
        }

        $Contact = new Contacts();
        $Contact->FirstName         = $request->FirstName;
        $Contact->LastName          = $request->MiddleName;
        $Contact->MiddleName        = $request->LastName;
        $Contact->DisplayName       = $request->DisplayName;
        $Contact->CompanyName       = $request->CompanyName;
        $Contact->Type              = $request->Type;
        $Contact->Title             = $request->Title;
        $Contact->Image             = $ImageName;
        $Contact->Phone             = $request->Phone;
        $Contact->Mobile            = $request->Mobile;
        $Contact->Email             = $request->Email;
        $Contact->Website           = $request->Website;
        $Contact->Suffix            = $request->Suffix;
        $Contact->IsCustomer        = $request->IsCustomer;
        $Contact->IsVendor          = $request->IsVendor;
        $Contact->BillingAddress    = $request->BillingAddress;
        $Contact->BillingCountry    = $request->BillingCountry;
        $Contact->BillingState      = $request->BillingState;
        $Contact->BillingCity       = $request->BillingCity;
        $Contact->BillingZipCode    = $request->BillingZipCode;
        $Contact->ShippingAddress   = $request->ShippingAddress;
        $Contact->ShippingCity      = $request->ShippingCity;
        $Contact->ShippingZipCode   = $request->ShippingZipCode;
        $Contact->ShippingCountry   = $request->ShippingCountry;
        $Contact->ShippingState     = $request->ShippingState;
        $Contact->Notes             = $request->Notes;
        $Contact->Status            = $request->Status;
        $Contact->TIN               = $request->TIN;
        $Contact->Attachment        = $request->Attachment;
        $Contact->AsOf              = $request->AsOf;
        $Contact->Reference         = $request->Reference;
        $Contact->OpeningBalance    = $request->OpeningBalance;
        $Contact->PaymentMethod     = $request->PaymentMethod;
        $Contact->DeliveryMethod    = $request->DeliveryMethod;
        $Contact->Status            = $request->Status;

        $Contact->save();

        $ContactID = $Contact->id;

        $ContactBalance = new ContactBalance();
        $ContactBalance->ContactID = $ContactID;
        $ContactBalance->Balance = $request->OpeningBalance;
        $ContactBalance->save();

        $ContactBalance = $ContactBalance->Balance;

        $ContactLedger = new ContactLedger();
        $ContactLedger->UserID              = $request->UserID;
        $ContactLedger->ContactID           = $ContactID;
        $ContactLedger->InvoiceID           = 0;
        $ContactLedger->PurchaseOrderID     = $request->PurchaseOrderID;
        $ContactLedger->PurchaseInvoiceID   = $request->PurchaseInvoiceID;
        $ContactLedger->MemoNo              = $request->MemoNo;
        $ContactLedger->Debit               = $request->Debit;
        $ContactLedger->Credit              = $request->Credit;
        $ContactLedger->Balance             = $ContactBalance;
        $ContactLedger->PaymentMethod       = $request->PaymentMethod;
        $ContactLedger->Notes               = $request->Notes;
        $ContactLedger->DueDate             = $request->DueDate;
        $ContactLedger->PaymentDate         = $request->IsApproved;
        $ContactLedger->IsApproved          = $request->PurchaseInvoiceID;
        $ContactLedger->Status              = $request->Status;

        $ContactLedger->save();

        return response()->json($Contact, 201);
    }

    public function contactTransaction(Request $request)
    {
        $ContactTransaction = new ContactLedger();
        $ContactTransaction->transaction($request);

        $BankTransaction = new BankLedger();
        $BankTransaction->transaction($request);

        return response()->json($ContactTransaction, 201);
    }

    public function updateContact(Request $request, Contacts $ContactID)
    {
        $ContactID->update($request->all());

        return response()->json($ContactID, 200);
    }

    public function destroyContact(Contacts $ContactID)
    {
        $ContactID->delete();

        return response()->json(null, 204);
    }

    public function contactLedger($ContactID=0, $UserID=0, $IsApproved=0, $Status=0, $DueDateFrom=0, $ToDueDate=0, $PaymentDateFrom=0, $ToPaymentDate=0, $FromDate=0, $ToDate=0)
    {

        return  ContactLedger::where('ContactID','=',$ContactID)->leftjoin('contacts','contacts.id','=','contact_ledgers.ContactID')->select('contacts.FirstName','contact_ledgers.Debit','contact_ledgers.Credit','contact_ledgers.Balance','contact_ledgers.created_at')->get();
        // $ContactLedger = new ContactLedger();
        // $ContactLedger->filterContactLedger($ContactID, $UserID, $IsApproved, $Status, $DueDateFrom, $ToDueDate, $PaymentDateFrom, $ToPaymentDate, $FromDate, $ToDate);

        // return $ContactLedger;
        // $ContactLedger = new ContactLedger();
        // $FilteredLedger = $ContactLedger->filterContactLedger($ContactID, $UserID, $IsApproved, $Status, $DueDateFrom, $ToDueDate, $PaymentDateFrom, $ToPaymentDate, $FromDate, $ToDate);

        // return $FilteredLedger;
    }

    public function filterContactList($ID=0, $Phone=0, $Email=0, $Status=0, $FromDate=0, $ToDate=0)
    {
        $Contacts = new Contacts();
        $FilteredContactList = $Contacts->filterContactList($ID, $Phone, $Email, $Status, $FromDate, $ToDate);

        return $FilteredContactList;
    }

    public function listCustomers()
    {
        $Customers = new Contacts();

        return $Customers->isCustomers();
    }

    public function listVendors()
    {
        $Vendors = new Contacts();

        return $Vendors->isVendors();
    }
}
