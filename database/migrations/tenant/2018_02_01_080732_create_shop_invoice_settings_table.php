<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_invoice_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ShopID')->unsigned();
            $table->bigInteger('FloorID')->unsigned()->nullable();
            $table->bigInteger('TerminalID')->unsigned()->nullable();
            $table->longText('Header')->nullable();
            $table->longText('Footer')->nullable();
            $table->boolean('ShowDiscount')->default(0)->nullable();
            $table->boolean('ShowPhone')->default(1)->nullable();
            $table->boolean('ShowInvoiceID')->default(1)->nullable();
            $table->boolean('ShowVatReg')->default(1)->nullable();
            $table->boolean('ShowProductID')->default(1)->nullable();
            $table->boolean('ShowTotalQty')->default(1)->nullable();
            $table->boolean('ShowHeader')->default(1)->nullable();
            $table->boolean('ShowFooter')->default(1)->nullable();
            $table->boolean('ShowLogo')->default(1)->nullable();
            $table->boolean('ShowTax')->default(1)->nullable();
            $table->boolean('ShowAddress')->default(1)->nullable();
            $table->boolean('ShowEmail')->default(1)->nullable();
            $table->string('SaleInvoiceSize', 50)->nullable();
            $table->string('PurchaseInvoiceSize', 50)->nullable();
            $table->string('FontSize', 50)->nullable();
            $table->string('FontFamily', 50)->nullable();
            $table->string('LogoWidth', 50)->nullable();
            $table->string('LogoHeight', 50)->nullable();
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
        Schema::dropIfExists('shop_invoice_settings');
    }
}
