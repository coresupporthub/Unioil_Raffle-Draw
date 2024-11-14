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
        Schema::create('retail_store', callback: function (Blueprint $table) {
            $table->uuid('store_id')->primary();
            $table->string('cluster_id');
            $table->string('region_name');
            $table->string('city_name');
            $table->string('store_name');
            $table->string('store_code');
            $table->string('store_status')->default('Enable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_store');
    }
};
