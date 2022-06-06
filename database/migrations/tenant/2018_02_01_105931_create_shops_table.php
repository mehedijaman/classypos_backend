<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Name', 500)->nullable();
            $table->string('LegalName', 500)->nullable();
            $table->string('Type', 500)->nullable();
            $table->string('Address', 500)->nullable();
            $table->string('City', 255)->nullable();
            $table->string('Province', 255)->nullable();
            $table->string('Phone', 255)->nullable();
            $table->string('Email', 255)->nullable();
            $table->string('Website', 255)->nullable();
            $table->string('Logo', 500)->nullable();
            $table->string('LicenceNo', 500)->nullable();
            $table->string('VatRegNo', 500)->nullable();
            $table->string('Notes', 500)->nullable();
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
        Schema::dropIfExists('shops');
    }
}
