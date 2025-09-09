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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_no', 50)->unique()->after('id');
            $table->string('first_name', 100)->after('employee_no');
            $table->string('last_name', 100)->after('first_name');
            $table->string('department', 100)->after('last_name');
            $table->string('position', 100)->after('department');
            $table->unsignedBigInteger('role_id')->nullable()->after('password');
            $table->string('status', 50)->default('Active')->after('role_id'); // Active, Inactive, Resigned
            
            // Drop existing name column as we now have first_name and last_name
            $table->dropColumn(['name', 'phone', 'location', 'about_me']);
            
            $table->index(['employee_no']);
            $table->index(['status']);
            $table->index(['department']);
        });
        
        // Add foreign key constraint after seeding default roles
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropIndex(['employee_no']);
            $table->dropIndex(['status']);
            $table->dropIndex(['department']);
            
            $table->dropColumn([
                'employee_no',
                'first_name', 
                'last_name',
                'department',
                'position',
                'role_id',
                'status'
            ]);
            
            // Restore original columns
            $table->string('name')->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('location')->nullable()->after('phone');
            $table->text('about_me')->nullable()->after('location');
        });
    }
};
