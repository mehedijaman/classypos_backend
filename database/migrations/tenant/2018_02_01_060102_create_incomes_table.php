<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('CategoryID')->unsigned();
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->bigInteger('TerminalID')->unsigned()->nullable();
            $table->bigInteger('FloorID')->unsigned()->nullable();
            $table->string('Account')->nullable();
            $table->decimal('Amount', 15, 4)->nullable();
            $table->string('Notes')->nullable();
            $table->dateTime('Date')->nullable();
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
        Schema::dropIfExists('incomes');
    }
}
