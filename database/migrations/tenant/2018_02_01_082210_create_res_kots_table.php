<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResKotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_kots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->bigInteger('UserID')->unsigned()->nullable();
            $table->bigInteger('TableID')->unsigned()->nullable();
            $table->bigInteger('StaffID')->unsigned()->nullable();
            $table->bigInteger('CustomerID')->unsigned()->nullable();
            $table->string('Guests', 255)->nullable();
            $table->string('Notes', 255)->nullable();
            $table->boolean('IsReady')->default(0)->nullable();
            $table->boolean('IsDelivered')->default(0)->nullable();
            $table->boolean('IsInvoiced')->default(0)->nullable();
            $table->boolean('IsComplete')->default(0)->nullable();
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
        Schema::dropIfExists('res_kots');
    }
}
