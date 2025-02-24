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
        Schema::table('product_lists', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
            $table->string('product_image')->nullable();
        });
    }

};
