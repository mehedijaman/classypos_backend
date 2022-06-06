<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('BankID')->unsigned();
            $table->string('ChequeNumber')->nullable();
            $table->string('RefChequeNumber')->nullable();
            $table->string('RefBank')->nullable();
            $table->decimal('Deposit', 15, 4)->nullable();
            $table->decimal('Withdraw', 15, 4)->nullable();
            $table->decimal('Balance', 15, 4)->nullable();
            $table->string('TxBy', 255)->nullable();
            $table->string('Notes', 500)->nullable();
            $table->boolean('Status')->nullable();
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
        Schema::dropIfExists('bank_ledgers');
    }
}
