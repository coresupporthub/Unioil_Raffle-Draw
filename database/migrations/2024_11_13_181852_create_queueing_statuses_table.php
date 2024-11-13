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
        Schema::create('queueing_statuses', function (Blueprint $table) {
            $table->uuid('queue_id')->primary();
            $table->integer('queue_number');
            $table->integer('items');
            $table->integer('total_items');
            $table->enum('status', ['inprogress', 'done']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queueing_statuses');
    }
};
