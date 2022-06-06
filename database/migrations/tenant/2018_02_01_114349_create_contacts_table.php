<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Type', 255)->nullable();
            $table->string('Title', 255)->nullable();
            $table->string('FirstName', 255)->nullable();
            $table->string('MiddleName', 255)->nullable();
            $table->string('LastName', 255)->nullable();
            $table->string('Suffix', 255)->nullable();
            $table->string('DisplayName', 255)->nullable();
            $table->string('CompanyName', 255)->nullable();
            $table->string('Phone', 50)->unique();
            $table->string('Mobile', 50)->unique();
            $table->string('Email', 255)->unique()->nullable();
            $table->string('Website', 255)->unique()->nullable();
            $table->string('BillingAddress', 500)->nullable();
            $table->string('BillingCity',250)->nullable();
            $table->string('BillingState', 250)->nullable();
            $table->string('BillingZipCode',250)->nullable();
            $table->string('BillingCountry',250)->nullable();
            $table->string('ShippingAddress', 500)->nullable();
            $table->string('ShippingCity',250)->nullable();
            $table->string('ShippingState', 250)->nullable();
            $table->string('ShippingZipCode',250)->nullable();
            $table->string('ShippingCountry',250)->nullable();
            $table->string('Image', 255)->nullable();
            $table->string('PaymentMethod', 500)->nullable();
            $table->string('DeliveryMethod', 500)->nullable();
            $table->string('TIN', 500)->nullable();
            $table->string('NID', 500)->nullable();
            $table->string('Attachment', 500)->nullable();
            $table->decimal('OpeningBalance', 15, 4)->nullable();
            $table->datetime('AsOf')->nullable();
            $table->text('Notes')->nullable();
            $table->string('Reference',250)->nullable();
            $table->boolean('IsCustomer')->nullable();
            $table->boolean('IsVendor')->nullable();
            $table->boolean('Status')->default(0)->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
