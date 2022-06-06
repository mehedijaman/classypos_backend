<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Name', 255);
            $table->string('Address', 255)->nullable();
            $table->decimal('OpeningBalance', 15, 4)->nullable();
            $table->string('AccountName', 255)->nullable();
            $table->string('AccountNumber', 255)->nullable();
            $table->boolean('IsDefault')->nullable();
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
        Schema::dropIfExists('banks');
    }
}
