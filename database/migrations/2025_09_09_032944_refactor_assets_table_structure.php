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
            // Add new columns
            $table->unsignedBigInteger('category_id')->nullable()->after('asset_tag');
            $table->unsignedBigInteger('vendor_id')->nullable()->after('category_id');
            $table->string('name', 100)->nullable()->after('vendor_id');
            $table->text('description')->nullable()->after('name');
            $table->date('warranty_end')->nullable()->after('warranty_until');
            
            // Add foreign key constraints
            $table->foreign('category_id')->references('id')->on('asset_categories')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            
            // Add indexes
            $table->index(['category_id']);
            $table->index(['vendor_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['category_id']);
            $table->dropForeign(['vendor_id']);
            
            // Drop columns
            $table->dropColumn([
                'category_id',
                'vendor_id', 
                'name',
                'description',
                'warranty_end'
            ]);
        });
    }
};
