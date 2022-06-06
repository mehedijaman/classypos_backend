<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReceiveProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receive_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ProductID')->unsigned()->nullable();
            $table->bigInteger('PurchaseID')->unsigned()->nullable();
            $table->string('BatchNo', 50)->nullable();
            $table->string('CustomID', 255)->nullable();
            $table->string('Model', 255)->nullable();
            $table->string('Description', 255)->nullable();
            $table->decimal('Qty', 15, 4)->nullable();
            $table->decimal('CostPrice', 15, 4)->nullable();
            $table->decimal('SalePrice', 15, 4)->nullable();
            $table->dateTime('ExpiredDate')->nullable();
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
        Schema::dropIfExists('purchase_receives_products_mappings');
    }
}
