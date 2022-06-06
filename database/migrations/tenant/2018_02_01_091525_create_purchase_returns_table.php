<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ContactID')->unsigned();
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->bigInteger('PurchaseID')->unsigned()->nullable();
            $table->string('MemoNo', 255)->nullable();
            $table->decimal('Total', 15, 4)->nullable();
            $table->string('ReturnReason', 500)->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
}
