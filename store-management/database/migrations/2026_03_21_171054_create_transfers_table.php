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
        Schema::create('transfers', function (Blueprint $table) {
                    
            $table->uuid('id')->primary();
            $table->string('external_id')->unique();
            $table->string('reference_no')->nullable();
            $table->string('external_status')->nullable();
            $table->string('from_warehouse')->nullable();
            $table->string('to_warehouse')->nullable();
            $table->integer('qty')->default(0);
            $table->integer('sku_count')->default(0);
            $table->enum('current_action', ['prepared', 'dropped'])->nullable();
            $table->boolean('is_missing_from_api')->default(false);
            $table->json('payload')->nullable();
            $table->timestamp('external_updated_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
