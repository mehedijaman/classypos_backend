<?php

namespace ClassyPOS\Models\Contacts;

use ClassyPOS\Models\Products\Products;
use ClassyPOS\Models\Purchase\Receive\PurchaseReceives;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Contacts extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "contacts";

    protected $fillable = [
        'Type',
        'Title',
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'DisplayName',
        'CompanyName',
        'Phone',
        'Mobile',
        'Email',
        'Website',
        'BillingAddress',
        'BillingCity',
        'BillingState',
        'BillingZipCode',
        'BillingCountry',
        'ShippingAddress',
        'ShippingCity',
        'ShippingState',
        'ShippingZipCode',
        'ShippingCountry',
        'Image',
        'PaymentMethod',
        'DeliveryMethod',
        'TIN',
        'Attachment',
        'OpeningBalance',
        'AsOf',
        'Notes',
        'Reference',
        'IsCustomer',
        'IsVendor',
        'Status',
    ];

    public function product()
    {
        return $this->hasMany(Products::class);
    }

    public function purchase()
    {
        return $this->hasMany(PurchaseReceives::class);
    }


    public function isCustomers()
    {
        $Customers = DB::table('contacts')->select(
            'contacts.id',
            'contacts.Type',
            'contacts.Title',
            'contacts.FirstName',
            'contacts.MiddleName',
            'contacts.LastName',
            'contacts.Suffix',
            'contacts.DisplayName',
            'contacts.CompanyName',
            'contacts.Phone',
            'contacts.Mobile',
            'contacts.Email',
            'contacts.Website',
            'contacts.BillingAddress',
            'contacts.BillingCity',
            'contacts.BillingState',
            'contacts.BillingZipCode',
            'contacts.BillingCountry',
            'contacts.ShippingAddress',
            'contacts.ShippingCity',
            'contacts.ShippingState',
            'contacts.ShippingZipCode',
            'contacts.ShippingCountry',
            'contacts.Image',
            'contacts.PaymentMethod',
            'contacts.DeliveryMethod',
            'contacts.TIN',
            'contacts.Attachment',
            'contacts.OpeningBalance',
            'contacts.AsOf',
            'contacts.Notes',
            'contacts.Reference',
            'contacts.IsCustomer',
            'contacts.IsVendor',
            'contacts.Status',

            'contacts.deleted_at',
            'contacts.created_at',
            'contacts.updated_at'
        )
            ->where('contacts.IsCustomer', '=', 1)
            ->whereNull('contacts.deleted_at')
            ->paginate(10);

        return json_encode($Customers);
    }

    public function isVendors()
    {
        $Vendors = DB::table('contacts')->select(
            'contacts.id',
            'contacts.Type',
            'contacts.Title',
            'contacts.FirstName',
            'contacts.MiddleName',
            'contacts.LastName',
            'contacts.Suffix',
            'contacts.DisplayName',
            'contacts.CompanyName',
            'contacts.Phone',
            'contacts.Mobile',
            'contacts.Email',
            'contacts.Website',
            'contacts.BillingAddress',
            'contacts.BillingCity',
            'contacts.BillingState',
            'contacts.BillingZipCode',
            'contacts.BillingCountry',
            'contacts.ShippingAddress',
            'contacts.ShippingCity',
            'contacts.ShippingState',
            'contacts.ShippingZipCode',
            'contacts.ShippingCountry',
            'contacts.Image',
            'contacts.PaymentMethod',
            'contacts.DeliveryMethod',
            'contacts.TIN',
            'contacts.Attachment',
            'contacts.OpeningBalance',
            'contacts.AsOf',
            'contacts.Notes',
            'contacts.Reference',
            'contacts.IsCustomer',
            'contacts.IsVendor',
            'contacts.Status',

            'contacts.deleted_at',
            'contacts.created_at',
            'contacts.updated_at'
        )
            ->where('contacts.IsVendor', '=', 1)
            ->whereNull('contacts.deleted_at')
            ->paginate(10);

        return json_encode($Vendors);
    }

    /**
     * Check Contact based on attributes
     * Filter Contact by
     *
     * @param $id
     * @param $Phone
     * @param $Email
     * @param $Status
     * @param $FromDate
     * @param $ToDate
     *
     * @return array
     */

    public function filterContactList($ID, $Phone, $Email, $Status, $FromDate, $ToDate)
    {
        /**
         * @ToDo
         * Future we should implement
         * Further filter
         * like
         * $AccountName, $AccountNumber, $AllowReturn, $HasAttribute, $Tags,
         */

        if ($FromDate == 0) {
            $FromDate = '2000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d');
            $ToDate = date('Y-m-d', strtotime($ToDate . '+1 day'));
        }

        if ($ID == 0 && $Phone == 0 && $Email == 0 && $Status == 0) {

            /**
             * 0000
             * If no filter is selected then
             * return all product
             */

            $Contacts = DB::table('contacts')->select(
                'contacts.id',
                        'contacts.Type',
                        'contacts.Title',
                        'contacts.FirstName',
                        'contacts.MiddleName',
                        'contacts.LastName',
                        'contacts.Suffix',
                        'contacts.contacts.DisplayName',
                        'contacts.CompanyName',
                        'contacts.Phone',
                        'contacts.Mobile',
                        'contacts.Email',
                        'contacts.Website',
                        'contacts.BillingAddress',
                        'contacts.BillingCity',
                        'contacts.BillingState',
                        'contacts.BillingZipCode',
                        'contacts.BillingCountry',
                        'contacts.ShippingAddress',
                        'contacts.ShippingCity',
                        'contacts.ShippingState',
                        'contacts.ShippingZipCode',
                        'contacts.ShippingCountry',
                        'contacts.Image',
                        'contacts.PaymentMethod',
                        'contacts.DeliveryMethod',
                        'contacts.TIN',
                        'contacts.Attachment',
                        'contacts.OpeningBalance',
                        'contacts.AsOf',
                        'contacts.Notes',
                        'contacts.Reference',
                        'contacts.IsCustomer',
                        'contacts.IsVendor',
                        'contacts.Status',

                        'contacts.deleted_at',
                        'contacts.created_at',
                        'contacts.updated_at'
            )
                ->whereBetween('contacts.created_at', [$FromDate, $ToDate])
                ->whereNull('contacts.deleted_at')
                ->paginate(10);

            $Contacts = json_encode($Contacts);

        }

        return response($Contacts);
    }
}
