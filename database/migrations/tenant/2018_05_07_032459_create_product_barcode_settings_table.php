<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBarcodeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_barcode_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ShopID')->unsigned();
            $table->string('ShopName', 250)->nullable();
            $table->boolean('ShowProductID')->nullable();
            $table->boolean('ShowShopName')->nullable();
            $table->boolean('ShowSalePrice')->nullable();
            $table->boolean('ShowExpireDate')->nullable();
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
        Schema::dropIfExists('product_barcode_settings');
    }
}
