<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleQuoteProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_quote_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('QuoteID')->unsigned()->nullable();
            $table->bigInteger('ProductLedgerID')->unsigned()->nullable();
            $table->decimal('Qty', 15, 4)->nullable();
            $table->decimal('Price', 15, 4)->nullable();
            $table->decimal('TaxTotal', 15, 4)->nullable();
            $table->decimal('ShippingCharge', 15, 4)->nullable();
            $table->decimal('PackagingCharge', 15, 4)->nullable();
            $table->decimal('OtherCharge', 15, 4)->nullable();
            $table->decimal('Discount', 15, 4)->nullable();
            $table->decimal('Total', 15, 4)->nullable();
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
        Schema::dropIfExists('sale_quote_product_mappings');
    }
}
