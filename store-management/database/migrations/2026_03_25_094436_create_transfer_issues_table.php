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
        Schema::create('transfer_issues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transfer_id');
            $table->string('issue_type');
            $table->text('description');
            $table->string('status')->default('open'); // open, resolved, closed
            $table->timestamp('reported_at')->nullable();

            $table->uuid('reported_by')->nullable();      // QC user
            $table->uuid('notified_user_id')->nullable(); // picker

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_issues');
    }
};
