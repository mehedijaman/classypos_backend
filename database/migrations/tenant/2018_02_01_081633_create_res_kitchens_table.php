<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResKitchensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_kitchens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->string('Name', 255)->nullable();
            $table->boolean('IsOpen')->nullable();
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
        Schema::dropIfExists('res_kitchens');
    }
}
