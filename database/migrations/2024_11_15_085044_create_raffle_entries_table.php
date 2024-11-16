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
        Schema::create('raffle_entries', function (Blueprint $table) {
            $table->uuid('entries_id')->primary();
            $table->uuid('customer_id');
            $table->string('event_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->string('serial_number');
            $table->uuid('qr_id');
            $table->foreign('qr_id')->references('qr_id')->on('qr_codes');
            $table->string('retail_store_code');
            $table->enum('claim_status', ['true', 'false', 'none'])->default('none');
            $table->enum('winner_status', ['true', 'false'])->default('false');
            $table->enum('winner_record', ['true', 'false'])->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffle_entries');
    }
};
