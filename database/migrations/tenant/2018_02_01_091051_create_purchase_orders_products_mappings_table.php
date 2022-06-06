<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersProductsMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('PurchaseOrderID')->unsigned()->nullable();
            $table->bigInteger('ProductID')->unsigned()->nullable();
            $table->string('Description', 255)->nullable();
            $table->string('Model', 255)->nullable();
            $table->decimal('Price', 15, 4)->nullable();
            $table->decimal('Qty', 15, 4)->nullable();
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
        Schema::dropIfExists('purchase_order_product_mappings');
    }
}
