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
        Schema::table('lots', function (Blueprint $table) {
            
             $table->timestamp('in_transit_at')->nullable()->after('status');
            $table->timestamp('delivered_at')->nullable()->after('in_transit_at');

            $table->text('delivery_notes')->nullable()->after('delivered_at');
            $table->string('delivery_photo_path')->nullable()->after('delivery_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lots', function (Blueprint $table) {
    
           $table->dropColumn([
                'runner_id',
                'assigned_at',
                'in_transit_at',
                'delivered_at',
                'delivery_notes',
                'delivery_photo_path',
            ]);
    
        });
    }
};
