<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->bigInteger('FloorID')->unsigned()->nullable();
            $table->bigInteger('TerminalID')->unsigned()->nullable();
            $table->bigInteger('KOTID')->unsigned();
            $table->bigInteger('InvoiceID')->unsigned();
            $table->string('Name', 255)->nullable();
            $table->string('Capacity', 50)->nullable();
            $table->boolean('IsBooked')->nullable();
            $table->boolean('Status')->nullable();
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
        Schema::dropIfExists('res_tables');
    }
}
