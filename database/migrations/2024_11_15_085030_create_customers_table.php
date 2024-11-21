<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('customer_id')->primary();
            $table->longText('full_name');
            $table->longText('age');
            $table->longText('region');
            $table->longText('province')->nullable();
            $table->longText('city');
            $table->longText('brgy');
            $table->longText('street')->nullable();
            $table->longText('mobile_number');
            $table->longText('email')->nullable();
            $table->uuid('qr_id');
            $table->foreign('qr_id')->references('qr_id')->on('qr_codes');
            $table->string('product_purchased');
            $table->uuid('store_id');
            $table->foreign('store_id')->references('store_id')->on('retail_store');
            $table->uuid('event_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
