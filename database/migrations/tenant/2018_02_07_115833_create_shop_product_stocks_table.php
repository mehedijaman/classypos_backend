<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_product_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned()->nullable();
            $table->bigInteger('TerminalID')->unsigned()->nullable();
            $table->bigInteger('ProductLedgerID')->unsigned();
            $table->decimal('Qty', 15, 4);
            $table->boolean('Status')->default(1);
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
        Schema::dropIfExists('shop_product_stocks');
    }
}
