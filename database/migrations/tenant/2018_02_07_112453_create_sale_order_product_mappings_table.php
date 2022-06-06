<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('OrderID')->unsigned()->nullable();
            $table->bigInteger('ProductLedgerID')->unsigned()->nullable();
            $table->decimal('Price', 15, 4)->nullable();
            $table->decimal('Qty', 15, 4)->nullable();
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
        Schema::dropIfExists('sale_order_product_mappings');
    }
}
