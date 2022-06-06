<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ContactID')->unsigned();
            $table->string('ReferenceNo', 255)->nullable();
            $table->boolean('IsApproved')->nullable();
            $table->boolean('IsDelivered')->nullable();
            $table->boolean('IsBilled')->nullable();
            $table->boolean('Status')->nullable();
            $table->string('Notes', 500)->nullable();
            $table->string('DeliveryAddress', 500)->nullable();
            $table->dateTime('DeliveryDate')->nullable();
            $table->decimal('SubTotal', 15, 4)->default(0.0000)->nullable();
            $table->decimal('ShippingCharge', 15, 4)->default(0.0000)->nullable();
            $table->decimal('TaxCharge', 15, 4)->default(0.0000)->nullable();
            $table->decimal('OtherCharge', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Total', 15, 4)->default(0.0000)->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
