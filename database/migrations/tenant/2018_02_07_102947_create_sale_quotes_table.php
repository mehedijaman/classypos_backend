<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned();
            $table->bigInteger('TerminalID')->unsigned();
            $table->bigInteger('ContactID')->unsigned()->nullable();
            $table->string('Title', 255)->nullable();
            $table->decimal('SubTotal', 15, 4)->nullable();
            $table->decimal('TaxTotal', 15, 4)->nullable();
            $table->decimal('ShippingCharge', 15, 4)->nullable();
            $table->decimal('PackagingCharge', 15, 4)->nullable();
            $table->decimal('OtherCharge', 15, 4)->nullable();
            $table->decimal('Discount', 15, 4)->nullable();
            $table->decimal('Total', 15, 4)->nullable();
            $table->string('Notes', 500)->nullable();
            $table->boolean('Status')->nullable();
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
        Schema::dropIfExists('sale_quotes');
    }
}
