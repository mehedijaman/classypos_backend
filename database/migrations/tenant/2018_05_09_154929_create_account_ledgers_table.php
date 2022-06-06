<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('AccountID')->unsigned();
            $table->string('Description', 500)->nullable();
            $table->decimal('Debit', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Credit', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Balance', 15, 4)->default(0.0000)->nullable();
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
        Schema::dropIfExists('account_ledgers');
    }
}
