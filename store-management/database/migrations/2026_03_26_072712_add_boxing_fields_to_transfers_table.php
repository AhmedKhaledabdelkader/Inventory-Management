<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('boxing_status')->default('not_boxed')->after('verification_status');
            $table->timestamp('boxed_at')->nullable()->after('boxing_status');
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['boxing_status', 'boxed_at']);
        });
    }
};