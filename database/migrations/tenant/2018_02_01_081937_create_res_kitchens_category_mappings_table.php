<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResKitchensCategoryMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_kitchen_category_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('KitchenID')->unsigned()->nullable();
            $table->bigInteger('CategoryID')->unsigned()->nullable();
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
        Schema::dropIfExists('res_kitchen_category_mappings');
    }
}
