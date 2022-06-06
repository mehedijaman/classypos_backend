<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ContactID')->unsigned();
            $table->bigInteger('PurchaseOrderID')->unsigned();
            $table->string('MemoNo')->nullable();
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
        Schema::dropIfExists('purchase_receives');
    }
}
