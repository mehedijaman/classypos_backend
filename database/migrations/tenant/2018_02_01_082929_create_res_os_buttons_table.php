<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResOsButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_os_buttons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID');
            $table->bigInteger('FloorID')->nullable();
            $table->bigInteger('TerminalID')->nullable();
            $table->bigInteger('ProductID');
            $table->string('DisplayText', 255)->nullable();
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
        Schema::dropIfExists('res_os_buttons');
    }
}
