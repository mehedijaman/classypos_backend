<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResMenuProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_menu_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('MenuID')->unsigned()->nullable();
            $table->bigInteger('ProductID')->unsigned()->nullable();
            $table->decimal('Qty', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Price', 15, 4)->default(0.0000)->nullable();
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
        Schema::dropIfExists('res_menu_product_mappings');
    }
}
