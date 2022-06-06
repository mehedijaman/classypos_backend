<?php

namespace ClassyPOS\Models\Banks;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bank extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $times = ['deleted_at'];

    protected $table = 'banks';

    protected $fillable = [
        'Name',
        'Address',
        'OpeningBalance',
        'AccountName',
        'AccountNumber',
        'IsDefault',
        'Status'
    ];


    /**
     * Check Product based on attributes
     * Filter Product by
     *
     * @param $ID
     * @param $Name
     * @param $IsDefault
     * @param $Status
     * @param $FromDate
     * @param $ToDate
     *
     * @return array
     */

    public function filterBankList($ID, $Name, $IsDefault, $Status, $FromDate, $ToDate)
    {
        /**
         * @ToDo
         * Future we should implement
         * Further filter
         * like
         * $AccountName, $AccountNumber, $AllowReturn, $HasAttribute, $Tags,
         */

        if ($FromDate == 0) {
            $FromDate = '0000-01-01';
        }

        if ($ToDate == 0) {
            $ToDate = date('Y-m-d', strtotime('+1 day'));
        }

        if ($ID == 0 && $Name == 0 && $IsDefault == 0 && $Status == 0) {

            /**
             * 0000
             * If no filter is selected then
             * return all product
             */

            $Banks = DB::table('banks')->select(
                'banks.id',

                'banks.Name',
                'banks.Address',
                'banks.OpeningBalance',
                'banks.AccountName',
                'banks.AccountNumber',
                'banks.IsDefault',
                'banks.Status',
                
                'banks.deleted_at',
                'banks.created_at',
                'banks.updated_at'
            )
                ->whereBetween('banks.created_at', [$FromDate, $ToDate])
                ->whereNull('banks.deleted_at')
                ->paginate(10);

            $Banks = json_encode($Banks);

        }

        return response($Banks);
    }
}
