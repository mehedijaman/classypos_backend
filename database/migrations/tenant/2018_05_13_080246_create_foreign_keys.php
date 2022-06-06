<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_ledgers', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('AccountID')->references('id')->on('accounts');
        });

        Schema::table('bank_balances', function($table) {
            $table->foreign('BankID')->references('id')->on('banks');
        });

        Schema::table('bank_ledgers', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('BankID')->references('id')->on('banks');
        });

        Schema::table('contact_balances', function($table) {
            $table->foreign('ContactID')->references('id')->on('contacts');
        });

        Schema::table('contact_ledgers', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ContactID')->references('id')->on('contacts');
            $table->foreign('InvoiceID')->references('id')->on('sale_invoices');
        });

        Schema::table('employees', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
        });

        Schema::table('employee_balances', function($table) {
            $table->foreign('EmployeeID')->references('id')->on('employees');
        });

        Schema::table('employee_ledgers', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('EmployeeID')->references('id')->on('employees');
        });

        Schema::table('expenses', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('CategoryID')->references('id')->on('expense_categories');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
        });

        Schema::table('incomes', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('CategoryID')->references('id')->on('income_categories');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
        });

        Schema::table('notices', function ($table){
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('ToUserID')->references('id')->on('users');
        });

        Schema::table('product_adjustment_mappings', function ($table){
            $table->foreign('ProductID')->references('id')->on('products');
        });

        Schema::table('product_adjustments', function ($table){
            $table->foreign('UserID')->references('id')->on('users');
        });

        Schema::table('product_barcode_settings', function ($table){
            $table->foreign('ShopID')->references('id')->on('shops');
        });

        Schema::table('product_ledgers', function ($table){
            $table->foreign('ProductID')->references('id')->on('products');
            $table->foreign('PurchaseID')->references('id')->on('purchase_receives');
            $table->foreign('WarehouseID')->references('id')->on('product_warehouses');
            $table->foreign('RackID')->references('id')->on('product_racks');
        });
        
        Schema::table('product_racks', function ($table){
            $table->foreign('WarehouseID')->references('id')->on('product_warehouses');
        });

        Schema::table('product_wastes', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('products', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('CategoryID')->references('id')->on('product_categories');
            $table->foreign('ContactID')->references('id')->on('contacts');
            $table->foreign('BrandID')->references('id')->on('product_brands');
            $table->foreign('TaxID')->references('id')->on('taxes');
        });

        Schema::table('purchase_order_product_mappings', function($table) {
            $table->foreign('PurchaseOrderID')->references('id')->on('purchase_orders');
            $table->foreign('ProductID')->references('id')->on('products');
        });

        Schema::table('purchase_orders', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ContactID')->references('id')->on('contacts');
        });

        Schema::table('purchase_receive_product_mappings', function($table) {
            $table->foreign('ProductID')->references('id')->on('products');
            $table->foreign('PurchaseID')->references('id')->on('purchase_receives');
        });

        Schema::table('purchase_receives', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ContactID')->references('id')->on('contacts');
            //$table->foreign('PurchaseOrderID')->references('id')->on('purchase_orders');
        });

        Schema::table('purchase_return_product_mappings', function($table) {
            $table->foreign('PRID')->references('id')->on('purchase_returns');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('purchase_returns', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('ContactID')->references('id')->on('contacts');
            $table->foreign('PurchaseID')->references('id')->on('purchase_receives');
        });

        Schema::table('sale_advance_product_mappings', function($table) {
            $table->foreign('AdvanceID')->references('id')->on('sale_advances');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
            $table->foreign('TaxID')->references('id')->on('taxes');
        });

        Schema::table('sale_advances', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ContactID')->references('id')->on('contacts');
        });

        Schema::table('sale_hold_product_mappings', function($table) {
            $table->foreign('HoldID')->references('id')->on('sale_holds');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
            $table->foreign('TaxID')->references('id')->on('taxes');
        });

        Schema::table('sale_holds', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
        });

        Schema::table('sale_invoice_product_mappings', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('InvoiceID')->references('id')->on('sale_invoices');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('sale_invoices', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('KOTID')->references('id')->on('res_kots');
            $table->foreign('OrderID')->references('id')->on('sale_orders');
            $table->foreign('AdvanceID')->references('id')->on('sale_advances');
            $table->foreign('ContactID')->references('id')->on('contacts');
        });

        Schema::table('sale_order_product_mappings', function($table) {
            $table->foreign('OrderID')->references('id')->on('sale_orders');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('sale_orders', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ContactID')->references('id')->on('contacts');
        });

        Schema::table('sale_quote_product_mappings', function($table) {
            $table->foreign('QuoteID')->references('id')->on('sale_quotes');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('sale_quotes', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ContactID')->references('id')->on('contacts');
        });

        Schema::table('sale_refunds', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ContactID')->references('id')->on('contacts');
            $table->foreign('InvoiceID')->references('id')->on('sale_invoices');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('shop_cash_drawers', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
        });

        Schema::table('shop_floors', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
        });

        Schema::table('shop_invoice_settings', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
        });

        Schema::table('shop_product_category_mappings', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('CategoryID')->references('id')->on('product_categories');
        });

        Schema::table('shop_product_inventories', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('shop_product_ledgers', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('shop_product_stocks', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('ProductLedgerID')->references('id')->on('product_ledgers');
        });

        Schema::table('shop_settings', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
        });

        Schema::table('shop_terminals', function($table) {
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
        });

        Schema::table('user_profiles', function($table) {
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('ShopID')->references('id')->on('shops');
            $table->foreign('FloorID')->references('id')->on('shop_floors');
            $table->foreign('TerminalID')->references('id')->on('shop_terminals');
            $table->foreign('KitchenID')->references('id')->on('res_kitchens');
        });

//        Schema::table('user_roles', function($table) {
//            $table->foreign('RoleCategoryID')->references('id')->on('user_role_categories');
//            $table->foreign('UserID')->references('id')->on('users');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
