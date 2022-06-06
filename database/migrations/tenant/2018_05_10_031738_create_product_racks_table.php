<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_racks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('WarehouseID')->unsigned();
            $table->string('Name', 255)->nullable();
            $table->string('Number', 255)->nullable();
            $table->boolean('Status')->default(1);
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
        Schema::dropIfExists('product_racks');
    }
}
