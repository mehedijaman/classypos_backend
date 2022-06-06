<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->string('Name', 250)->nullable();
            $table->string('Code', 250)->nullable();
            $table->dateTime('DateOfBirth')->nullable();
            $table->string('MaritalStatus', 250)->nullable();
            $table->string('Gender', 50)->nullable();
            $table->string('Qualification', 250)->nullable();
            $table->text('Address')->nullable();
            $table->string('Phone', 50)->nullable();
            $table->string('Email', 250)->nullable();
            $table->dateTime('JoiningDate')->nullable();
            $table->dateTime('TerminationDate')->nullable();
            $table->boolean('Status')->nullable();
            $table->string('BloodGroup', 10)->nullable();
            $table->string('BankName', 250)->nullable();
            $table->string('BranchName', 250)->nullable();
            $table->string('AccountNumber', 250)->nullable();
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
        Schema::dropIfExists('employees');
    }
}
