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
        Schema::create('regional_cluster', function (Blueprint $table) {
            $table->uuid('cluster_id')->primary();
            $table->string('cluster_name');
            $table->string('cluster_status')->default('Enable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regional_cluster');
    }
};
