<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleAdvanceProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_advance_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('AdvanceID')->unsigned();
            $table->bigInteger('ProductLedgerID')->unsigned();
            $table->decimal('Qty', 15,4);
            $table->bigInteger('TaxID')->unsigned()->nullable();
            $table->decimal('SalePrice', 15, 4);
            $table->decimal('Discount', 15, 4);
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
        Schema::dropIfExists('sale_advance_product_mappings');
    }
}
