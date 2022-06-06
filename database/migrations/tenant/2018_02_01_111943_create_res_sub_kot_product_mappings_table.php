<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResSubKotProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_sub_kot_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('SubKOTID')->unsigned()->nullable();
            $table->bigInteger('ProductID')->unsigned()->nullable();
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->decimal('Qty', 15, 4)->nullable();
            $table->string('Notes', 500)->nullable();
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
        Schema::dropIfExists('res_sub_kot_product_mappings');
    }
}
