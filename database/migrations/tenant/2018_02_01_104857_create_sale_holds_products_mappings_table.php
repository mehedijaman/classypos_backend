<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleHoldsProductsMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_hold_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('HoldID')->unsigned();
            $table->bigInteger('TaxID')->unsigned()->nullable();
            $table->bigInteger('ProductLedgerID')->unsigned();
            $table->decimal('Qty', 15, 4)->nullable();
            $table->decimal('SalePrice', 15, 4)->nullable();
            $table->decimal('Discount', 15, 4)->nullable();
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
        Schema::dropIfExists('sale_hold_product_mappings');
    }
}
