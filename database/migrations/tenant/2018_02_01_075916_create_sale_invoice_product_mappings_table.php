<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleInvoiceProductMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_invoice_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('InvoiceID')->unsigned();
            $table->bigInteger('ProductLedgerID')->unsigned();
            $table->decimal('Qty', 15, 4)->default(0.0000)->nullable();
            $table->decimal('CostPrice', 15, 4)->nullable();
            $table->decimal('SalePrice', 15, 4)->default(0.0000)->nullable();
            $table->decimal('TaxTotal', 15, 4)->default(0.0000)->nullable();
            $table->decimal('Discount', 15, 4)->default(0.0000)->nullable();
            $table->decimal('TotalPrice', 15, 4)->default(0.0000)->nullable();
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
        Schema::dropIfExists('sale_invoice_product_mappings');
    }
}
