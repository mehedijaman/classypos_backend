<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('CategoryID')->unsigned()->nullable();
            $table->string('Name')->nullable();
            $table->decimal('Price', 15, 4)->nullable();
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
        Schema::dropIfExists('res_menus');
    }
}
