<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Name', 255)->nullable();;
            $table->text('Address')->nullable();
            $table->string('Phone',50)->nullable();
            $table->string('Email', 250)->nullable();
            $table->string('Website', 250)->nullable();
            $table->string('Country', 250)->nullable();
            $table->string('FinancialYearFrom', 255)->nullable();
            $table->string('BooksBeginingFrom', 255)->nullable();
            $table->string('TIN', 255)->nullable();
            $table->string('Logo', 255)->nullable();
            $table->dateTime('CurrentDate')->nullable();
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
        Schema::dropIfExists('company');
    }
}
