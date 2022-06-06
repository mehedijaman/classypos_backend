<?php

namespace ClassyPOS\Models\Accounts\Income;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Income extends Model
{
    use SoftDeletes, UsesTenantConnection;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = "incomes";

    protected $fillable = [
        'UserID',
        'CategoryID',
        'ShopID',
        'FloorID',
        'TerminalID',
        'AccountName',
        'Account',
        'Amount',
        'Notes',
        'Date'
    ];

    /**
     * Check Income based on attributes
     * Filter Income by
     *
     * @param $CategoryID
     * @param $ShopID
     * @param $FloorID
     * @param $TerminalID
     * @param $FromDate
     * @param $ToDate
     *
     * @return array
     */

    public function filterIncomeList($CategoryID, $ShopID, $FloorID, $TerminalID, $FromDate, $ToDate)
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

        if ($CategoryID == 0 && $ShopID == 0 && $FloorID == 0 && $TerminalID == 0) {

            /**
             * 0000
             * If no filter is selected then
             * return all product
             */

            $Incomes = DB::table('incomes')->select(
                'incomes.id',

                'incomes.CategoryID',
                'income_categories.Name as CategoryName',

                'incomes.ShopID',
                'shops.Name as ShopName',

                'incomes.FloorID',
                'shop_floors.Name as FloorName',

                'incomes.TerminalID',
                'shop_terminals.Name as TerminalName',

                'incomes.Amount',
                'incomes.Account',
                'incomes.Notes',
                'incomes.Date',

                'incomes.deleted_at',
                'incomes.created_at',
                'incomes.updated_at'
            )
                ->leftJoin('income_categories', 'incomes.CategoryID', '=', 'income_categories.id')
                ->leftJoin('shops', 'incomes.ShopID', '=', 'shops.id')
                ->leftJoin('shop_floors', 'incomes.ShopID', '=', 'shop_floors.id')
                ->leftJoin('shop_terminals', 'incomes.TerminalID', '=', 'shop_terminals.id')
                ->whereBetween('incomes.created_at', [$FromDate, $ToDate])
                ->whereNull('incomes.deleted_at')
                ->paginate(10);

            $Incomes = json_encode($Incomes);

        }

        return response($Incomes);
    }
}
