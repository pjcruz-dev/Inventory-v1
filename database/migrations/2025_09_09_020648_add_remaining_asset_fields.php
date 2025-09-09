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
        Schema::table('assets', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('manufacturer');
            $table->string('asset_owner')->nullable()->after('serial_number');
            $table->string('warranty_vendor')->nullable()->after('asset_owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'serial_number',
                'asset_owner',
                'warranty_vendor'
            ]);
        });
    }
};
