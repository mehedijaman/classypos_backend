<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAdjustmentMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_adjustment_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ProductID')->unsigned();
            $table->decimal('Qty', 15,4)->nullable();
            $table->decimal('SalePrice', 15,4)->nullable();
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
        Schema::dropIfExists('product_adjustment_mappings');
    }
}
