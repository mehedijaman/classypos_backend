<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('EmployeeID')->unsigned()->nullable();
            $table->decimal('Debit', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Credit', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Balance', 15, 4)->default(0.0000)->nullable();
            $table->string('PaymentMethod', 255)->nullable();
            $table->string('Notes', 500)->nullable();
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
        Schema::dropIfExists('employee_ledgers');
    }
}
