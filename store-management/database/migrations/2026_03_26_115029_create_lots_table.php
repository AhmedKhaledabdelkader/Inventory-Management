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

Schema::create('lots', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('lot_code')->unique();
    $table->string('destination');
    $table->unsignedInteger('total_boxes')->default(0);
    $table->unsignedInteger('total_items')->default(0);
    $table->string('status')->default('pending'); // pending, ready, done
    $table->uuid('created_by')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
