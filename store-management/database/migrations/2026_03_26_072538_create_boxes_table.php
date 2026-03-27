<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('transfer_id');
            $table->string('box_code')->unique();

            $table->string('destination');
            $table->unsignedInteger('box_number');       // 1, 2, 3...
            $table->unsignedInteger('total_boxes');      // total created for this transfer

            $table->string('status')->default('pending'); // pending, assigned, shipped, delivered

            $table->uuid('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxes');
    }
};