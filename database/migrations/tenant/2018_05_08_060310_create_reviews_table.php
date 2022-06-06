<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('Name', 255)->nullable();
        $table->string('Email', 255)->nullable();
        $table->string('Phone', 50)->nullable();
        $table->string('Rating', 10)->nullable();
        $table->string('Reaction', 50)->nullable();
        $table->string('Notes', 255)->nullable();
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
        Schema::dropIfExists('reviews');
    }
}
