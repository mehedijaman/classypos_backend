<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('UserID')->unsigned();
            $table->bigInteger('CategoryID')->unsigned();
            $table->bigInteger('ContactID')->unsigned();
            $table->bigInteger('BrandID')->unsigned()->nullable();
            $table->bigInteger('TaxID')->unsigned()->nullable();
            $table->string('Type', 255)->nullable();
            $table->string('Name', 255);
            $table->text('Description')->nullable();
            $table->string('SKU', 25)->nullable();
            $table->string('UPC',25)->nullable();
            $table->string('MPN', 25)->nullable();
            $table->string('EAN', 25)->nullable();
            $table->string('ISBN', 25)->nullable();
            $table->string('Image', 255)->nullable();
            $table->decimal('OpeningQty', 15, 4)->default(0.0000);
            $table->decimal('Qty', 15, 4)->default(0.0000);
            $table->decimal('MinQtyLevel', 8, 2)->nullable();
            $table->decimal('CostPrice', 15, 4)->default(0.0000)->nullable();
            $table->decimal('SalePrice', 15, 4)->default(0.0000);
            $table->string('Unit', 255)->nullable();
            $table->boolean('AllowInventory')->default(1)->nullable();
            $table->boolean('AllowNegative')->default(0)->nullable();
            $table->boolean('AllowReturn')->default(1)->nullable();
            $table->boolean('AllowTax')->default(1)->nullable();
            $table->boolean('Status')->default(1)->nullable();
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
        Schema::dropIfExists('products');
    }
}
