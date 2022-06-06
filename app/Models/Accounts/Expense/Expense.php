<?php

namespace ClassyPOS\Models\Accounts\Expense;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "expenses";

    protected $fillable = [
        'CategoryID',
        'ShopID',
        'FloorID',
        'TerminalID',
        'Amount',
        'Account',
        'ExpenseBy',
        'Notes',
        'Date'
    ];

    /**
     * Check Expense based on attributes
     * Filter Expense by
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

    public function filterExpenseList($CategoryID, $ShopID, $FloorID, $TerminalID, $FromDate, $ToDate)
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

            $Expenses = DB::table('expenses')->select(
                'expenses.id',

                'expenses.CategoryID',
                'expense_categories.Name as CategoryName',

                'expenses.ShopID',
                'shops.Name as ShopName',

                'expenses.FloorID',
                'shop_floors.Name as FloorName',

                'expenses.TerminalID',
                'shop_terminals.Name as TerminalName',

                'expenses.Amount',
                'expenses.Account',
                'expenses.ExpenseBy',
                'expenses.Notes',
                'expenses.Date',

                'expenses.deleted_at',
                'expenses.created_at',
                'expenses.updated_at'
            )
                ->leftJoin('expense_categories', 'expenses.CategoryID', '=', 'expense_categories.id')
                ->leftJoin('shops', 'expenses.ShopID', '=', 'shops.id')
                ->leftJoin('shop_floors', 'expenses.ShopID', '=', 'shop_floors.id')
                ->leftJoin('shop_terminals', 'expenses.TerminalID', '=', 'shop_terminals.id')
                ->whereBetween('expenses.created_at', [$FromDate, $ToDate])
                ->whereNull('expenses.deleted_at')
                ->paginate(10);

            $Expenses = json_encode($Expenses);

        }

        return response($Expenses);
    }
}
