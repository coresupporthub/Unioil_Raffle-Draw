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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('act_id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('action')->nullable();
            $table->string('result')->nullable();
            $table->string('device')->nullable();
            $table->string('page_route')->nullable();
            $table->string('api_calls')->nullable();
            $table->string('request_type')->nullable();
            $table->string('session_id')->nullable();
            $table->string('sent_data')->nullable();
            $table->string('response_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
