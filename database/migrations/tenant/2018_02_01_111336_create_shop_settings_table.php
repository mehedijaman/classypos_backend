<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned();
            $table->boolean('IsRestaurant')->nullable();
            $table->boolean('IsServiceCharge')->nullable();
            $table->decimal('ServiceCharge', 15, 4)->nullable();
            $table->boolean('IsTips')->nullable();
            $table->boolean('IsTax')->nullable();
            $table->boolean('IsOrder')->nullable();
            $table->boolean('IsHold')->nullable();
            $table->boolean('IsAdvance')->nullable();
            $table->boolean('IsBarcode')->nullable();
            $table->boolean('IsRefund')->nullable();
            $table->boolean('IsDiscount')->nullable();
            $table->boolean('SalesAcross')->nullable();
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
        Schema::dropIfExists('shop_settings');
    }
}
