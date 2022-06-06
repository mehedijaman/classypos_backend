<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\{
    Schema\Blueprint, Migrations\Migration
};

class CreateSaleAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_advances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned();
            $table->bigInteger('TerminalID')->unsigned();
            $table->bigInteger('ContactID')->unsigned()->nullable();
            $table->string('Name', 255)->nullable();
            $table->string('Phone', 255)->nullable();
            $table->string('Email',255)->nullable();
            $table->string('Address', 500)->nullable();
            $table->decimal('Amount', 15, 4)->nullable();
            $table->decimal('Due', 15, 4)->nullable();
            $table->string('Notes', 500)->nullable();
            $table->dateTime('DeliveryDate')->nullable();
            $table->boolean('Status');
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
        Schema::dropIfExists('sale_advances');
    }
}
