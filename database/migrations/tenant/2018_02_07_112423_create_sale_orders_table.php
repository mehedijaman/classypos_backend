<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned();
            $table->bigInteger('TerminalID')->unsigned();
            $table->bigInteger('ContactID')->unsigned()->nullable();
            $table->string('ReferenceNo', 255)->nullable();
            $table->string('Name', 255)->nullable();
            $table->string('Email', 255)->nullable();
            $table->string('Phone', 50)->nullable();
            $table->string('Address', 500)->nullable();
            $table->decimal('SubTotal', 15, 4)->nullable();
            $table->decimal('TaxTotal', 15, 4)->nullable();
            $table->decimal('ShippingCharge', 15, 4)->nullable();
            $table->decimal('OtherCharge', 15, 4)->nullable();
            $table->decimal('Discount', 15, 4)->nullable();
            $table->decimal('Total', 15, 4)->nullable();
            $table->decimal('AdvancePaid', 15, 4)->nullable();
            $table->decimal('Due', 15, 4)->nullable();
            $table->string('500')->nullable();
            $table->boolean('IsConfirmed')->nullable();
            $table->boolean('IsDelivered')->nullable();
            $table->dateTime('OrderDate')->nullable();
            $table->dateTime('DeliveryDate')->nullable();
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
        Schema::dropIfExists('sale_orders');
    }
}
