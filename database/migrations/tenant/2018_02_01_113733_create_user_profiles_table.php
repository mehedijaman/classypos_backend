<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('ShopID')->unsigned()->nullable();
            $table->bigInteger('FloorID')->unsigned()->nullable();
            $table->bigInteger('TerminalID')->unsigned()->nullable();
            $table->bigInteger('KitchenID')->unsigned()->nullable();
            $table->string('Phone', 255)->unique()->nullable();
            $table->string('FirstName', 255)->nullable();
            $table->string('LastName', 255)->nullable();
            $table->string('Address', 500)->nullable();
            $table->string('City', 255)->nullable();
            $table->string('Province', 255)->nullable();
            $table->string('ZipCode', 50)->nullable();
            $table->string('Country', 255)->nullable();
            $table->dateTime('DateOfBirth')->nullable();
            $table->string('Image')->nullable();
            $table->dateTime('LastLogin')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
