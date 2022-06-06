<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnsProductsMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('PRID')->unsigned();
            $table->bigInteger('ProductLedgerID')->unsigned()->nullable();
            $table->decimal('Qty', 15, 4)->nullable();
            $table->decimal('Price', 15, 4)->nullable();
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
        Schema::dropIfExists('purchase_return_product_mappings');
    }
}
