<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ContactID')->unsigned();
            $table->bigInteger('InvoiceID')->unsigned();
            $table->bigInteger('PurchaseOrderID')->unsigned()->nullable();
            $table->bigInteger('PurchaseInvoiceID')->unsigned()->nullable();
            $table->string('MemoNo', 250)->nullable();
            $table->decimal('Debit', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Credit', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Balance', 15, 4)->default(0.0000)->nullable();
            $table->string('PaymentMethod', 255)->nullable();
            $table->string('Notes', 255)->nullable();
            $table->dateTime('DueDate')->nullable();
            $table->dateTime('PaymentDate')->nullable();
            $table->boolean('IsApproved')->nullable();
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
        Schema::dropIfExists('contact_ledgers');
    }
}
