<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('title')->nullable()->after('reference_no');
            $table->text('items_names')->nullable()->after('sku_count');
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['title', 'items_names']);
        });
    }
};