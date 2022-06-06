<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned();
            $table->bigInteger('TerminalID')->unsigned();
            $table->bigInteger('ContactID')->unsigned()->nullable();
            $table->bigInteger('InvoiceID')->unsigned()->nullable();
            $table->bigInteger('ProductLedgerID')->unsigned();
            $table->decimal('Qty', 15, 4);
            $table->decimal('SubTotal', 15, 4);
            $table->decimal('TaxTotal', 15, 4);
            $table->decimal('Discount', 15, 4);
            $table->decimal('TotalPrice', 15, 4);
            $table->string('RefundReason', 500)->nullable();
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
        Schema::dropIfExists('sale_refunds');
    }
}
