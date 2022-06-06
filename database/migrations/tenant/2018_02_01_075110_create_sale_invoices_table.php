<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned();
            $table->bigInteger('TerminalID')->unsigned();
            $table->bigInteger('KOTID')->unsigned()->nullable();
            $table->bigInteger('OrderID')->unsigned()->nullable();
            $table->bigInteger('AdvanceID')->unsigned()->nullable();
            $table->bigInteger('ContactID')->unsigned()->nullable();
            $table->decimal('TotalCost', 15, 4)->default(0.0000);
            $table->decimal('SubTotal', 15, 4)->default(0.0000);
            $table->decimal('TaxTotal', 15, 4)->default(0.0000);
            $table->decimal('ServiceCharge', 15, 4)->default(0.0000);
            $table->decimal('Discount', 15, 4)->default(0.0000);
            $table->decimal('Total', 15, 4)->default(0.0000);
            $table->decimal('PaidMoney', 15, 4)->default(0.0000);
            $table->decimal('ReturnMoney', 15, 4)->default(0.0000);
            $table->boolean('IsVoid')->default(0)->nullable();
            $table->boolean('IsRefunded')->default(0)->nullable();
            $table->boolean('IsPaid')->nullable();
            $table->boolean('IsApproved')->nullable();
            $table->boolean('Status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_invoices');
    }
}
