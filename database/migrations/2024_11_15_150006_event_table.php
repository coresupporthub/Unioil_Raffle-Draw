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
        Schema::create('event', callback: function (Blueprint $table) {
            $table->uuid('event_id')->primary();
            $table->string('event_name');
            $table->string('event_start');
            $table->string('event_end');
            $table->string('event_price');
            $table->string('event_description');
            $table->string('event_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
